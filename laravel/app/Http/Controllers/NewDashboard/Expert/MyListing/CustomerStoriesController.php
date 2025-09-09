<?php

namespace App\Http\Controllers\NewDashboard\Expert\MyListing;

use App\Http\Controllers\Controller;
use App\Models\ExpertCustomerStory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerStoriesController extends Controller
{
    protected function ensureOwner(Request $request, string $expertId): void
    {
        if ($request->user() && (string) $request->user()->id !== (string) $expertId) {
            abort(response()->json(['type' => 'error','status' => 403,'message' => 'Forbidden'], 403));
        }
    }

    /** GET /api/v2/expert/{expert_id}/customer-stories */
    public function index(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $stories = ExpertCustomerStory::where('expert_id', $expert_id)
                ->latest('created_at')
                ->get();

            $data = $stories->map(fn ($s) => $this->transform($s))->all();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $data,
            ]);

        } catch (\Throwable $e) {
            Log::error('Customer stories index failed: '.$e->getMessage());
            return response()->json(
                [
                'type' => 'error',
                'status' => 500,
                'message' => 'Failed to fetch customer stories.'],
                500
            );
        }
    }

    /** POST /api/v2/expert/{expert_id}/customer-stories  (multipart/form-data) */
    public function store(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'problem' => ['required','string','max:10000'],
            'title'   => ['required','string','max:255'],
            'solution'       => ['required','string','max:20000'],
            'result'         => ['required','string','max:10000'],
            'images'         => ['sometimes','array','max:3'],
            'images.*'       => ['file','image','mimes:jpg,jpeg,png,webp','max:8192'], // 8MB each
        ]);

        try {
            $paths = $this->storeImages($request, $expert_id);

            $story = ExpertCustomerStory::create([
                'expert_id'      => $expert_id,
                'images'         => $paths ?: [],
                'title'          => $data['title'],
                'problem'        => $data['problem'],
                'solution'       => $data['solution'],
                'result'         => $data['result'],
            ]);

            return response()->json([
                'type'   => 'success',
                'status' => 201,
                'data'   => $this->transform($story),
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Create customer story failed: '.$e->getMessage());
            return response()->json(
                [
                'type' => 'error',
                'status' => 500,
                'message' => 'Failed to create customer story.'
            ], 500);
        }
    }

    /** PUT /api/v2/expert/{expert_id}/customer-stories/{id}  (multipart/form-data allowed) */
    public function update(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $rules = [
            'title'   => ['sometimes','required','string','max:255'],
            'problem' => ['sometimes','required','string','max:10000'],
            'solution' => ['sometimes','required','string','max:20000'],
            'result'   => ['sometimes','required','string','max:10000'],
        ];

        if ($request->hasFile('images')) {
            $rules['images'] = ['sometimes','array','max:3'];
            $rules['images.*'] = ['file','image','mimes:jpg,jpeg,png,webp','max:8192'];
        }

        $data = $request->validate($rules);
        return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $request->all(),
            ]);

        try {
            $story = ExpertCustomerStory::where('id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (! $story) {
                return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
            }

            // Replace images if new ones were provided (default behavior)
            if ($request->hasFile('images')) {
                foreach (($story->images ?? []) as $old) {
                    if ($old && Storage::disk('s3')->exists($old)) {
                        Storage::disk('s3')->delete($old);
                    }
                }
                $newImages = $this->storeImages($request, $expert_id);
                $story->images = $newImages;
            } elseif (!$request->has('keep_existing_images')) {
                // No images sent and no keep flag = remove all images
                foreach (($story->images ?? []) as $old) {
                    if ($old && Storage::disk('s3')->exists($old)) {
                        Storage::disk('s3')->delete($old);
                    }
                }
                $story->images = [];
            }

            // Save all changes including images
            $story->fill(collect($data)->except(['images'])->toArray());
            $story->save();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $this->transform($story),
            ]);

        } catch (\Throwable $e) {
            Log::error('Update customer story failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to update customer story.'], 500);
        }
    }

    /** DELETE /api/v2/expert/{expert_id}/customer-stories/{id} */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $story = ExpertCustomerStory::where('id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (! $story) {
                return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
            }

            // remove files
            foreach (($story->images ?? []) as $old) {
                if ($old && Storage::disk('s3')->exists($old)) {
                    Storage::disk('s3')->delete($old);
                }
            }

            $story->delete();

            return response()->json(['type' => 'success','status' => 200,'message' => 'Customer story deleted.'], 200);

        } catch (\Throwable $e) {
            Log::error('Delete customer story failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to delete customer story.'], 500);
        }
    }

    /* ---------- Helpers ---------- */

    protected function storeImages(Request $request, string $expertId): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }
        $stored = [];
        foreach ((array) $request->file('images') as $file) {
            if (!$file) {
                continue;
            }
            $ext  = $file->getClientOriginalExtension() ?: 'jpg';
            $name = Str::uuid()->toString().'.'.$ext;
            $path = $file->storeAs("customer-stories/{$expertId}/images", $name, 's3');
            $stored[] = $path;
        }
        return $stored;
    }

    protected function transform(ExpertCustomerStory $s): array
    {
        return [
            'id'             => $s->id,
            'expert_id'      => (string) $s->expert_id,
            'title'          => $s->title,
            'problem'        => $s->problem,
            'solution'       => $s->solution,
            'result'         => $s->result,
            'images'         => collect($s->images ?: [])->map(fn($path) => [
                                    'url' => $path,
                                    'alt' => ''
                                ])->toArray(),
            'duration'       => optional($s->created_at)->format('M Y'),
            'created_at'     => optional($s->created_at)->toIso8601String(),
            'updated_at'     => optional($s->updated_at)->toIso8601String(),
        ];
    }
}