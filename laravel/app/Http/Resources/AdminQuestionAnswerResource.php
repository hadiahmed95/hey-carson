<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminQuestionAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'client_id' => $this->client_id,
            'content'   => $this->content,
            'client' => [
                'full_name' => $this->client->first_name . ' ' . $this->client->last_name
            ],
            'answers'   => AnswerResource::collection($this->answers),
            'status'    => $this->status,
            'created_at'=> $this->created_at
        ];
    }
}
