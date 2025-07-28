<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminQuestionRequest;
use App\Http\Requests\CreateQuestionRequest;
use App\Http\Requests\UpdateQuestionStatusRequest;
use App\Http\Resources\AdminQuestionAnswerResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
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
     * @param  AdminQuestionRequest  $request
     * @return ResourceCollection
     */
    public function all(AdminQuestionRequest $request): ResourceCollection
    {
        $validatedData = $request->validated();
        $status = $validatedData['status'];
        $search = $validatedData['search'] ?? null;
        $questions = $this->questionRepository->allQuestions($status, $search);
        return QuestionResource::collection($questions);
    }

    /**
     * @param  Question  $question
     * @return AdminQuestionAnswerResource
     */
    public function show(Question $question): AdminQuestionAnswerResource
    {
        $question->load([
            'answers',
            'answers.expert' => function ($query) {
                $query->withTrashed();
            },
            'answers.expert.profile',
            'client.profile'
        ]);
        return new AdminQuestionAnswerResource($question);
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

    /**
     * @param UpdateQuestionStatusRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(UpdateQuestionStatusRequest $request, Question $question): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $this->questionRepository->updateStatus($question, $validatedData['status']);
            return response()->json(['status' => true]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }
}
