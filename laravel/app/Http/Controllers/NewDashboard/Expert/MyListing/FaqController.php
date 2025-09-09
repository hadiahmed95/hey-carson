<?php

namespace App\Http\Controllers\NewDashboard\Expert\MyListing;

use App\Models\ExpertFaq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController
{
    protected function ensureOwner(Request $request)
    {
        $expertUser = $request->user();
        if (! $expertUser) {
            return response()->json([
                'type'    => 'error',
                'status'  => 401,
                'message' => 'Unauthorized: Missing expert user',
            ], 401);
        }
        return $expertUser;
    }

    /**
     * GET /api/v2/expert/{expert_id}/faqs
     */
    public function index(Request $request): JsonResponse
    {
        $expertUser = $this->ensureOwner($request);

        try {
            $faqs = ExpertFaq::where('expert_id', $expertUser->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $faqs->map(function (ExpertFaq $f) {
                return [
                    'id'         => (string) $f->id,
                    'expert_id'  => (string) $f->expert_id,
                    'title'   => $f->question,
                    'answer'     => $f->answer,
                    'created_at' => optional($f->created_at)->toIso8601String(),
                    'updated_at' => optional($f->updated_at)->toIso8601String(),
                ];
            })->all();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => $data,
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Expert FAQ index failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while fetching FAQs.',
            ], 500);
        }
    }

    /**
     * POST /api/v2/expert/{expert_id}/faqs
     */
    public function store(Request $request): JsonResponse
    {
        $expertUser = $this->ensureOwner($request);

        $data = $request->validate([
            'question' => ['required','string','max:255'],
            'answer'   => ['required','string','max:5000'],
        ]);

        try {
            $faq = ExpertFaq::create([
                'expert_id' => $expertUser->id,
                'question'  => $data['question'],
                'answer'    => $data['answer'],
            ]);

            return response()->json([
                'type'   => 'success',
                'status' => 201,
                'data'   => [
                    'id'         => (string) $faq->id,
                    'expert_id'  => (string) $faq->expert_id,
                    'question'   => $faq->question,
                    'answer'     => $faq->answer,
                    'created_at' => optional($faq->created_at)->toIso8601String(),
                    'updated_at' => optional($faq->updated_at)->toIso8601String(),
                ],
            ], 201, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Create Expert FAQ failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while creating the FAQ.',
            ], 500);
        }
    }

    /**
     * PUT /api/v2/expert/{expert_id}/faqs/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $expertUser = $this->ensureOwner($request);

        $data = $request->validate([
            'question' => ['sometimes','required','string','max:255'],
            'answer'   => ['sometimes','required','string','max:5000'],
        ]);

        try {
            $faq = ExpertFaq::where('id', $id)
                ->where('expert_id', $expertUser->id)
                ->first();

            if (! $faq) {
                return response()->json([
                    'type'    => 'error',
                    'status'  => 404,
                    'message' => 'Not found',
                ], 404);
            }

            $faq->fill($data)->save();

            return response()->json([
                'type'   => 'success',
                'status' => 200,
                'data'   => [
                    'id'         => (string) $faq->id,
                    'expert_id'  => (string) $faq->expert_id,
                    'question'   => $faq->question,
                    'answer'     => $faq->answer,
                    'created_at' => optional($faq->created_at)->toIso8601String(),
                    'updated_at' => optional($faq->updated_at)->toIso8601String(),
                ],
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            Log::error('Update Expert FAQ failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while updating the FAQ.',
            ], 500);
        }
    }

    /**
     * DELETE /api/v2/expert/{expert_id}/faqs/{id}
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $expertUser = $this->ensureOwner($request);

        try {
            $faq = ExpertFaq::where('id', $id)
                ->where('expert_id', $expertUser->id)
                ->first();

            if (! $faq) {
                return response()->json([
                    'type'    => 'error',
                    'status'  => 404,
                    'message' => 'Not found',
                ], 404);
            }

            $faq->delete();

            return response()->json([
                'type'    => 'success',
                'status'  => 200,
                'message' => 'FAQ deleted.',
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Delete Expert FAQ failed: '.$e->getMessage());
            return response()->json([
                'type'    => 'error',
                'status'  => 500,
                'message' => 'An error occurred while deleting the FAQ.',
            ], 500);
        }
    }

}
