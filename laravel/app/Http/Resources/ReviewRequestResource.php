<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'expert' => [
                'id' => $this->expert->id,
                'name' => $this->expert->first_name . ' ' . $this->expert->last_name,
                'photo' => $this->expert->photo,
                'company_type' => $this->expert->company_type,
                'recurringExpert' => false, // Optional logic
                'isShopexpertUser' => (bool) $this->hired_on_shopexperts,
                'rank' => '',
                'url' => $this->expert->url ?? '',
                'storeTitle' => $this->expert->store_title ?? '',
            ],
            'reviewer' => [
                'id' => auth()->id(),
                'name' => $this->client_full_name,
                'photo' => auth()->user()?->photo ?? '',
                'storeTitle' => $this->client_company_name ?? '',
                'url' => $this->client_company_website ?? '',
                'recurringClient' => (bool) $this->repeated_client,
                'rating' => '',
                'comment' => '',
                'recommendation' => '',
                'isShopexpertUser' => false,
            ],
            'id' => $this->id,
            'projectId' => $this->project_id,
            'projectTitle' => $this->project->name,
            'postedAt' => $this->created_at->toDateString(),
            'responses' => [$this->message],
            'projectValue' => $this->value_range ?? '',
            'reviewSource' => '',
            'status' => 'Pending Review',
        ];
    }
}
