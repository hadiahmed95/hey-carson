<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'expert_id'     => $this->expert_id,
            'question_id'   => $this->question_id,
            'edited'        => $this->edited,
            'content'       => $this->content,
            'created_at'    => $this->created_at,
            'expert'        => [
                'id'        => $this->expert->id,
                'full_name' => $this->expert->first_name . ' ' . $this->expert->last_name,
                'photo'     => $this->expert->photo,
                'profile' => [
                    'role' => $this->expert->profile->role,
                ]
            ]

        ];
    }
}
