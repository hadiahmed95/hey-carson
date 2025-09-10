<?php

namespace App\Http\Controllers\NewDashboard\Expert\MyListing;

use App\Http\Controllers\Controller;
use App\Models\PackagedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackagedServiceController extends Controller
{
    protected function ensureOwner(Request $request, string $expertId): void
    {
        if ($request->user() && (string) $request->user()->id !== (string) $expertId) {
            abort(response()->json([
                'type'    => 'error',
                'status'  => 403,
                'message' => 'Forbidden',
            ], 403));
        }
    }
    public function index(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $packagedServices = PackagedService::where('expert_id', $expert_id)
                ->get();

            $data = $packagedServices->map(function (PackagedService $ps) {
                return [
                    'id'          => (string) $ps->id,
                    'expert_id'   => (string) $ps->expert_id,
                    'title'        => $ps->title,
                    'description' => $ps->description,
                    'price'       => $ps->price,
                    'delivery_time'    => $ps->delivery_time,
                    'thumbnail'    => $ps->thumbnail,
                    'created_at'  => $ps->created_at->toIso8601String(),
                    'updated_at'  => $ps->updated_at->toIso8601String(),
                ];
            })->all();

            return response()->json([
                'type' => 'success',
                'status' => 200,
                'data' => $data,
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            Log::error('Error fetching packaged services: ' . $e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while fetching packaged services.',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'title'         => ['required','string','max:160'],
            'description'   => ['nullable','string','max:5000'],
            'price'         => ['required','numeric','min:0','max:999999.99'],
            'delivery_time' => ['nullable','string','max:100'],
            'thumbnail'     => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        try {
            $thumbPath = $this->storeThumbnail($request, $expert_id);
            $ps = PackagedService::create([
                'expert_id'     => $expert_id,
                'title'         => $data['title'],
                'description'   => $data['description']   ?? null,
                'price'         => $data['price'],
                'delivery_time' => $data['delivery_time'] ?? null,
                'thumbnail'     => $$thumbPath,
            ]);

            return response()->json([
                'type'   => 'success',
                'status' => 201,
                'data'   => $this->transform($ps),
            ], 201, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Create PackagedService failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while creating the packaged service.',
            ], 500);
        }
    }


    public function update(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'title'         => ['sometimes','required','string','max:160'],
            'description'   => ['sometimes','nullable','string','max:5000'],
            'price'         => ['sometimes','required','numeric','min:0','max:999999.99'],
            'delivery_time' => ['sometimes','nullable','string','max:100'],
            'thumbnail'     => ['sometimes','nullable','file','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        try {
            $ps = PackagedService::where('expert_id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (!$ps) {
                return response()->json([
                    'type'    => 'error',
                    'status'  => 404,
                    'message' => 'Not found',
                ], 404);
            }

            if ($request->hasFile('thumbnail')) {
                if ($ps->thumbnail && Storage::disk('public')->exists($ps->thumbnail)) {
                    Storage::disk('public')->delete($ps->thumbnail);
                }
                $ps->thumbnail = $this->storeThumbnail($request, $expert_id);
            }

            $ps->fill(collect($data)->except('thumbnail')->toArray())->save();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $this->transform($ps),
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Update PackagedService failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while updating the packaged service.',
            ], 500);
        }
    }


    public function destroy(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $ps = PackagedService::where('id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (!$ps) {
                return response()->json([
                    'type'    => 'error',
                    'status'  => 404,
                    'message' => 'Not found',
                ], 404);
            }

            if ($ps->thumbnail && Storage::disk('public')->exists($ps->thumbnail)) {
                Storage::disk('public')->delete($ps->thumbnail);
            }

            $ps->delete();

            return response()->json([
                'type'    => 'success',
                'status'  => 200,
                'message' => 'Packaged service deleted.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Delete PackagedService failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while deleting the packaged service.',
            ], 500);
        }
    }

    protected function transform(PackagedService $ps): array
    {
        return [
            'id'            => (string) $ps->id,
            'expert_id'     => (string) $ps->expert_id,
            'title'         => $ps->title,
            'description'   => $ps->description,
            'price'         => $ps->price,
            'delivery_time' => $ps->delivery_time,
            'thumbnail'     => $this->publicUrl($ps->thumbnail),
            'created_at'    => optional($ps->created_at)->toIso8601String(),
            'updated_at'    => optional($ps->updated_at)->toIso8601String(),
        ];
    }
    protected function storeThumbnail(Request $request, string $expertId): ?string
    {
        if (! $request->hasFile('thumbnail')) {
            return null;
        }
        $file = $request->file('thumbnail');
        $ext  = $file->getClientOriginalExtension() ?: 'jpg';
        $name = Str::uuid()->toString().'.'.$ext;

        return $file->storeAs(
            "packaged-services/{$expertId}/thumbnails",
            $name,
            'public'
        );
    }

    protected function publicUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
