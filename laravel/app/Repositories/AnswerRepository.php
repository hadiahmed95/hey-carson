<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AnswerRepository
{
    /**
     * @return Builder
     */
    private function query(): Builder
    {
        return Question::query();
    }

    /**
     * When type is self, list all question/Answers, where answers to that
     * question is associated with the current user(Expert). When type is
     * others, list all question/Answers, where the questions have no answers
     * or the questions are not associated with the current user(Expert),
     *
     * @param  string  $type
     * @param  string|null  $search
     * @return LengthAwarePaginator
     */
    public function all(string $type, ?string $search): LengthAwarePaginator
    {
        $query = $this->query();
        $query->where('status', 'completed');

        if ($type === "others") {
            $query->where(function ($builder) {
                $builder
                    ->doesntHave("answers")
                    ->orWhereHas("answers", function ($builder) {
                        $builder->whereNot("expert_id", \Auth::user()->id);
                    });
            });
        } elseif ($type === "self") {
            $query->whereHas("answers", function ($query) {
                $query->where("expert_id", \Auth::user()->id);
            });
        }

        if ($search) {
            $search = "%" . $search . "%";
            $query->where(function ($query) use ($search) {
                $query->where("content", "like", $search);
            });
        }

        return $query
            ->with([
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
     * Create new Answer.
     *
     * @param  User  $createdBy
     * @param $data
     * @return void
     */
    public function create(User $createdBy, $data): void
    {
        Answer::create([
            'expert_id' => $createdBy->id,
            'content' => $data['content'],
            'question_id' => $data['question_id'],
        ]);
    }

    /**
     * Edit Answer.
     *
     * @param  Answer  $answer
     * @param $data
     * @return void
     */
    public function edit(Answer $answer, $data): void
    {
        $answer->update([
            'content' => $data['content'],
            'edited' => true
        ]);
    }

}
