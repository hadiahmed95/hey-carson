<?php

namespace App\Http\Controllers\NewDashboard\Expert\MyListing;

use App\Http\Controllers\Controller;
use App\Models\ExpertOfferedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OfferedServicesController extends Controller
{
    protected function ensureOwner(Request $request, string $expertId): void
    {
        if ($request->user() && (string) $request->user()->id !== (string) $expertId) {
            abort(response()->json([
                'type' => 'error', 'status' => 403, 'message' => 'Forbidden',
            ], 403));
        }
    }

    /** GET /api/v2/expert/{expert_id}/offered-services */
    public function index(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $rows = ExpertOfferedService::where('expert_id', $expert_id)
                ->latest('created_at')
                ->get();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $rows->map(fn ($r) => $this->transform($r))->all(),
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Offered services index failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to fetch offered services.'], 500);
        }
    }

    /** GET /api/v2/expert/{expert_id}/offered-services/{id} */
    public function show(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $row = ExpertOfferedService::where('id', $id)->where('expert_id', $expert_id)->first();
        if (! $row) {
            return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
        }

        return response()->json([
            'type'   => 'success',
            'status' => 200,
            'data'   => $this->transform($row),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /** POST /api/v2/expert/{expert_id}/offered-services */
    public function store(Request $request): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'title'        => ['required','string','max:180'],
            'serviceCategory' => ['required','string','max:180'],
            'subcategories'   => ['nullable','array','max:3'],
            'subcategories.*' => ['nullable','string','max:180'],
        ]);

        try {
            // Normalize subcategories to exactly 3 items
            $subcategories = $this->normalizeSubcategories($data['subcategories'] ?? []);

            $row = ExpertOfferedService::create([
                'expert_id'       => $expert_id,
                'category_id'     => Str::uuid()->toString(),
                'category_name'   => $data['title'],

                'subservice1_id'   => null,
                'subservice1_name' => $subcategories[0] ?? null,
                'subservice2_id'   => null,
                'subservice2_name' => $subcategories[1] ?? null,
                'subservice3_id'   => null,
                'subservice3_name' => $subcategories[2] ?? null,
            ]);

            return response()->json([
                'type'   => 'success',
                'status' => 201,
                'data'   => $this->transform($row),
            ], 201, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Create offered service failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to create offered service.'], 500);
        }
    }

    /** PUT /api/v2/expert/{expert_id}/offered-services/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'title'        => ['sometimes','required','string','max:180'],
            'serviceCategory' => ['sometimes','required','string','max:180'],
            'subcategories'   => ['sometimes','array','max:3'],
            'subcategories.*' => ['nullable','string','max:180'],
        ]);

        try {
            $row = ExpertOfferedService::where('id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (! $row) {
                return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
            }

            // Update category name if provided
            if (array_key_exists('title', $data)) {
                $row->category_name = $data['title'];
            }

            // Update subcategories if provided
            if (array_key_exists('subcategories', $data)) {
                $subcategories = $this->normalizeSubcategories($data['subcategories'] ?? []);
                $row->subservice1_name = $subcategories[0] ?? null;
                $row->subservice2_name = $subcategories[1] ?? null;
                $row->subservice3_name = $subcategories[2] ?? null;
            }

            $row->save();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $this->transform($row),
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Update offered service failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to update offered service.'], 500);
        }
    }

    /** DELETE /api/v2/expert/{expert_id}/offered-services/{id} */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $expert_id = auth()->id();
        $this->ensureOwner($request, $expert_id);

        try {
            $row = ExpertOfferedService::where('id', $id)->where('expert_id', $expert_id)->first();
            if (! $row) {
                return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
            }

            $row->delete();

            return response()->json(['type' => 'success','status' => 200,'message' => 'Offered service deleted.'], 200);

        } catch (\Throwable $e) {
            Log::error('Delete offered service failed: '.$e->getMessage());
            return response()->json(['type' => 'error','status' => 500,'message' => 'Failed to delete offered service.'], 500);
        }
    }

    /* ---------------- helpers ---------------- */

    /** Ensure exactly 3 subcategories, null padded */
    protected function normalizeSubcategories(array $subcategories): array
    {
        $subcategories = array_values(array_filter($subcategories, fn($s) => !empty($s)));
        while (count($subcategories) < 3) {
            $subcategories[] = null;
        }
        return array_slice($subcategories, 0, 3);
    }

    protected function transform(ExpertOfferedService $r): array
    {
        $subcategories = array_values(array_filter([
            $r->subservice1_name,
            $r->subservice2_name,
            $r->subservice3_name,
        ]));

        return [
            'id'           => (string) $r->id,
            'title'        => $r->category_name,
            'subcategories' => $subcategories,
            'serviceCategory' => $r->category_name, // Keep both for compatibility
            'created_at'   => optional($r->created_at)->toIso8601String(),
            'updated_at'   => optional($r->updated_at)->toIso8601String(),
        ];
    }
}
