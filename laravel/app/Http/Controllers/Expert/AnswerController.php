<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnswerRequest;
use App\Http\Requests\AnswerRequest;
use App\Http\Requests\EditAnswerRequest;
use App\Http\Resources\QuestionAnswerResource;
use App\Models\Answer;
use App\Repositories\AnswerRepository;
use Mockery\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection as ResourceCollection;


class AnswerController extends Controller
{
    private AnswerRepository $answerRepository;

    /**
     * @param AnswerRepository $answerRepository
     */
    public function __construct(AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @param AnswerRequest $request
     * @return ResourceCollection
     */
    public function all(AnswerRequest $request): ResourceCollection
    {
        $validatedData = $request->validated();
        $type = $validatedData['type'];
        $search = $validatedData['search'] ?? null;

        $questions = $this->answerRepository->all($type, $search);

        return QuestionAnswerResource::collection($questions);
    }

    /**
     * @param CreateAnswerRequest $request
     * @return JsonResponse
     */
    public function create(CreateAnswerRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $this->answerRepository->create(\Auth::user(), $data);

            return response()->json(['status' => true]);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * @param EditAnswerRequest $request
     * @param Answer $answer
     * @return JsonResponse
     */
    public function edit(EditAnswerRequest $request, Answer $answer): JsonResponse
    {
        if ($answer->expert_id === \Auth::user()->id) {
            try {
                $data = $request->validated();

                $this->answerRepository->edit($answer, $data);
                return response()->json(['status' => true]);
            } catch (Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()]);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'unauthorised'], 401);
        }
    }
}
