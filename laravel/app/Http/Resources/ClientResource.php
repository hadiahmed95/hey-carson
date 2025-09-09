<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ClientResource
 *
 * Transforms client data for API responses in the admin dashboard.
 * Formats user data with additional metrics for clients management.
 *
 * @package App\Http\Resources
 */
class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstName = $this->first_name ?? '';
        $lastName = $this->last_name ?? '';
        $name = ($firstName || $lastName) ? trim($firstName . ' ' . $lastName) : 'N/A';
        return [
            'id' => $this->id,
            'first_name' => $this->first_name ? $this->first_name : '',
            'last_name' => $this->last_name ? $this->last_name : '',
            'name' => $name,
            'email' => $this->email ?? 'N/A',
            'photo' => $this->photo ?? null,
            'url' => $this->url ?? 'N/A',
            'shopify_plan' => $this->shopify_plan ?? 'N/A',
            'created_at' => $this->created_at ?? 'N/A',
            'direct_messages_count' => $this->direct_messages_count ?? 0,
            'quote_requests_count' => $this->quote_requests_count ?? 0,
            'lifetime_spend' => $this->lifetime_spend ?? 0,
        ];
    }
}
