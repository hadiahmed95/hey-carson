<?php

namespace App\Http\Controllers\NewDashboard\Expert\MyListing;

use App\Http\Controllers\Controller;
use App\Models\ExpertOfferedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function index(Request $request, string $expert_id): JsonResponse
    {
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
    public function show(Request $request, string $expert_id, string $id): JsonResponse
    {
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
    public function store(Request $request, string $expert_id): JsonResponse
    {
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'category_id'   => ['required','string','max:64'],
            'category_name' => ['required','string','max:180'],

            'subservices'           => ['nullable','array','max:3'],
            'subservices.*.id'      => ['nullable','string','max:64'],
            'subservices.*.name'    => ['required_with:subservices.*.id','nullable','string','max:180'],
        ]);

        try {
            [$s1, $s2, $s3] = $this->normalizeSubservices($data['subservices'] ?? []);

            $row = ExpertOfferedService::create([
                'expert_id'       => $expert_id,
                'category_id'     => $data['category_id'],
                'category_name'   => $data['category_name'],

                'subservice1_id'   => $s1['id'] ?? null,
                'subservice1_name' => $s1['name'] ?? null,
                'subservice2_id'   => $s2['id'] ?? null,
                'subservice2_name' => $s2['name'] ?? null,
                'subservice3_id'   => $s3['id'] ?? null,
                'subservice3_name' => $s3['name'] ?? null,
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

    /**
 * PUT /api/v2/expert/{expert_id}/offered-services/{id}
 */
    public function update(Request $request, string $expert_id, string $id): JsonResponse
    {
        $this->ensureOwner($request, $expert_id);

        $data = $request->validate([
            'category_id'        => ['sometimes','required','string','max:64'],
            'category_name'      => ['sometimes','required','string','max:180'],

            'subservices'        => ['sometimes','array','max:3'],
            'subservices.*.id'   => ['nullable','string','max:64'],
            'subservices.*.name' => ['required_with:subservices.*.id','nullable','string','max:180'],
        ]);

        try {
            $row = ExpertOfferedService::where('id', $id)
                ->where('expert_id', $expert_id)
                ->first();

            if (! $row) {
                return response()->json(['type' => 'error','status' => 404,'message' => 'Not found'], 404);
            }

            // Update category if provided
            if (array_key_exists('category_id', $data)) {
                $row->category_id   = $data['category_id'];
            }
            if (array_key_exists('category_name', $data)) {
                $row->category_name = $data['category_name'];
            }

            // Update subservices if provided (0–3)
            if (array_key_exists('subservices', $data)) {
                [$s1, $s2, $s3] = $this->normalizeSubservices($data['subservices'] ?? []);
                $row->subservice1_id   = $s1['id']   ?? null;
                $row->subservice1_name = $s1['name'] ?? null;
                $row->subservice2_id   = $s2['id']   ?? null;
                $row->subservice2_name = $s2['name'] ?? null;
                $row->subservice3_id   = $s3['id']   ?? null;
                $row->subservice3_name = $s3['name'] ?? null;
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
    public function destroy(Request $request, string $expert_id, string $id): JsonResponse
    {
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

    /** Ensure exactly 0–3 objects like [{id,name}] */
    protected function normalizeSubservices(array $subs): array
    {
        $subs = array_values($subs);
        $subs = array_map(function ($s) {
            if (is_string($s)) {
                return ['id' => null, 'name' => $s];
            }
            return [
                'id'   => $s['id']   ?? null,
                'name' => $s['name'] ?? null,
            ];
        }, $subs);
        while (count($subs) < 3) {
            $subs[] = null;
        }
        return $subs;
    }

    protected function transform(ExpertOfferedService $r): array
    {
        $subservices = array_values(array_filter([
            $r->subservice1_name ? ['id' => $r->subservice1_id, 'name' => $r->subservice1_name] : null,
            $r->subservice2_name ? ['id' => $r->subservice2_id, 'name' => $r->subservice2_name] : null,
            $r->subservice3_name ? ['id' => $r->subservice3_id, 'name' => $r->subservice3_name] : null,
        ]));

        return [
            'id'           => (string) $r->id,
            'expert_id'    => (string) $r->expert_id,
            'category'     => [
                'id'   => $r->category_id,
                'name' => $r->category_name,
            ],
            'subservices'  => $subservices,
            'created_at'   => optional($r->created_at)->toIso8601String(),
            'updated_at'   => optional($r->updated_at)->toIso8601String(),
        ];
    }
}
