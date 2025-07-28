<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateQuestionRequest;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\QuestionAnswerResource;
use App\Repositories\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as ResourceCollection;
use Mockery\Exception;

class QuestionController extends Controller
{
    private QuestionRepository $questionRepository;

    /**
     * @param QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param QuestionRequest $request
     * @return ResourceCollection
     */
    public function all(QuestionRequest $request): ResourceCollection
    {
        $validatedData = $request->validated();
        $type = $validatedData['type'];
        $search = $validatedData['search'] ?? null;

        $questions = $this->questionRepository->all($type, $search);

        return QuestionAnswerResource::collection($questions);
    }

    /**
     * @param CreateQuestionRequest $request
     * @return JsonResponse
     */
    public function create(CreateQuestionRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $this->questionRepository->create(\Auth::user(), $data);

            return response()->json(['status' => true]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }
}
