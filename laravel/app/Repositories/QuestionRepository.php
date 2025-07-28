<?php

namespace App\Repositories;

use App\Models\AdminSetting;
use App\Models\Question;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class QuestionRepository
{
    /**
     * @return Builder
     */
    private function query(): Builder
    {
        return Question::query();
    }

    /**
     * When type is self, list all questions related to the current user.
     * When type is others, list all questions which are not related to
     * the current user.
     *
     * @param  string  $type  having values 'self' or 'others'
     * @param  string|null  $search
     * =
     * @return LengthAwarePaginator
     */
    public function all(string $type, ?string $search): LengthAwarePaginator
    {
        $query = $this->query();

        if ($type === 'self') {
            $query->where('client_id', \Auth::user()->id);
        } elseif ($type === 'others') {
            $query->whereNot('client_id', \Auth::user()->id);
        }

        if ($search) {
            $search = '%' . $search . '%';
            $query->where(function ($query) use ($search) {
                $query->where('content', 'like', $search);
            });
        }

        return $query
            ->with([
                'answers',
                'answers.expert' => function ($query) {
                    $query->withTrashed();
                },
                'answers.expert.profile',
                'client.profile'
            ])
            ->latest('updated_at')
            ->paginate(15);
    }

    /**
     * Fetch questions with respect to the status, and search params.
     *
     * @param  string  $status
     * @param  string|null  $search
     * @return LengthAwarePaginator
     */
    public function allQuestions(string $status, ?string $search): LengthAwarePaginator
    {
        $query = $this->query();
        if ($search) {
            $search = "%" . $search . "%";
            $query->where(function ($query) use ($search) {
                $query->where("content", "like", $search);
            });
        }

        if ($status !== 'all') {
            $query->where("status", $status);
        }

        return $query
            ->latest('updated_at')
            ->paginate(15);
    }

    /**
     * Create new question.
     *
     * @param  User  $createdBy
     * @param $data
     * @return void
     */
    public function create(User $createdBy, $data): void
    {
        $moderateQuestions = AdminSetting::query()
            ->where('type', 'moderate_questions')
            ->first()
            ->value ?? true;

        $status = 'created';
        if (!$moderateQuestions) {
            $status = 'completed';
        }

        Question::create([
            'client_id' => $createdBy->id,
            'content'   => $data['content'],
            'status'    => $status
        ]);
    }

    /**
     * Update Status of the question.
     *
     * @param  Question  $question
     * @param $status
     * @return void
     */
    public function updateStatus(Question $question, $status): void
    {
        $question->update([
            'status' => $status
        ]);
    }
}
