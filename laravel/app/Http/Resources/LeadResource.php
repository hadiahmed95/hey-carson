<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LeadResource
 * 
 * Transforms lead (client) data for API responses in the admin dashboard.
 * Formats user data with additional metrics for leads management.
 * 
 * @package App\Http\Resources
 */
class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'photo' => $this->photo,
            'url' => $this->url,
            'shopify_plan' => $this->shopify_plan,
            'created_at' => $this->created_at,
            'direct_messages_count' => $this->direct_messages_count ?? 0,
            'quote_requests_count' => $this->quote_requests_count ?? 0,
            'lifetime_spend' => $this->lifetime_spend ?? 0,
        ];
    }
}