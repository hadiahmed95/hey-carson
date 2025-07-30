<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WrittenReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'expert' => [
                'id' => $this->expert->id,
                'name' => $this->expert->first_name . ' ' . $this->expert->last_name,
                'photo' => $this->expert->photo,
                'company_type' => $this->expert->company_type,
                'recurringExpert' => false,
                'isShopexpertUser' => $this->expert->hired_on_shopexperts ?? false,
                'rank' => '',
                'storeUrl' => $this->expert->store_url ?? '',
                'storeTitle' => $this->expert->store_title ?? '',
            ],
            'reviewer' => [
                'id' => $this->client_id,
                'name' => $this->client->first_name . ' ' . $this->client->last_name,
                'photo' => $this->client->photo ?? '',
                'storeTitle' => $this->client->store_title ?? '',
                'storeUrl' => $this->client->store_url ?? '',
                'recurringClient' => false,
                'rating' => $this->rate ?? '', // average or overall
                'quality' => $this->quality ?? '',
                'communication' => $this->communication ?? '',
                'timeToStart' => $this->timeToStart ?? '',
                'valueForMoney' => $this->valueForMoney ?? '',
                'comment' => $this->comment ?? '',
                'recommendation' => $this->recommendation ?? '',
                'valueRange' => $this->valueRange ?? '', // 'anonymous' if anonymous
                'isShopexpertUser' => false,
            ],
            'id' => $this->id,
            'projectId' => $this->project_id,
            'projectTitle' => optional($this->project)->name,
            'postedAt' => $this->created_at->toDateString(),
            'response' => $this->response,
            'projectValue' => $this->value_range ?? '',
            'reviewSource' => $this->review_source ?? '',
            'status' => $this->status ?? 'Approved',
        ];
    }
}
