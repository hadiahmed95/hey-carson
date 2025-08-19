<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rate,
            'comment' => $this->comment,
            'recommendation' => $this->formatRecommendation($this->recommendation),
            'projectValue' => $this->formatProjectValue($this->valueRange),
            'reviewSource' => $this->review_source,
            'status' => $this->status,
            'postedAt' => $this->created_at?->format('M d, Y'),
            'reviewer' => [
                'id' => $this->client?->id,
                'name' => $this->client ? $this->client->first_name . ' ' . $this->client->last_name : null,
                'photo' => $this->client?->photo,
                'storeTitle' => $this->client?->url,
                'storeUrl' => $this->client?->url,
                'recurringClient' => $this->getRecurringClientStatus(),
                'isShopexpertUser' => $this->client?->usertype === 'paid',
            ],
            'expert' => [
                'id' => $this->expert?->id,
                'name' => $this->expert ? $this->expert->first_name . ' ' . $this->expert->last_name : null,
                'photo' => $this->expert?->photo,
                'company_type' => $this->expert?->profile?->expert_type ?? 'Agency',
                'recurringExpert' => $this->getRecurringExpertStatus(),
                'isShopexpertUser' => $this->expert?->usertype === 'paid',
                'rank' => $this->expert?->profile?->role ?? 'Senior',
                'storeUrl' => $this->expert?->url,
                'storeTitle' => $this->expert?->url,
            ],
        ];
    }

    /**
     * Format recommendation from snake_case to Title Case
     */
    private function formatRecommendation($recommendation)
    {
        if (!$recommendation) return 'Not specified';
        
        // Convert snake_case to Title Case
        return ucwords(str_replace('_', ' ', strtolower($recommendation)));
    }

    /**
     * Format project value from 100_1000 to $100-$1000
     */
    private function formatProjectValue($projectValue)
    {
        if (!$projectValue) return 'Not specified';
        
        // Handle formats like "100_1000" or "1000_5000"
        if (strpos($projectValue, '_') !== false) {
            $parts = explode('_', $projectValue);
            if (count($parts) === 2) {
                // Convert to numeric values first, then format
                $min = is_numeric($parts[0]) ? (float)$parts[0] : 0;
                $max = is_numeric($parts[1]) ? (float)$parts[1] : 0;
                
                return '$' . number_format($min, 0) . '-$' . number_format($max, 0);
            }
        }
        
        // Return as-is if already formatted
        return $projectValue;
    }

    /**
     * Check if client is a recurring client for this expert.
     */
    private function getRecurringClientStatus(): bool
    {
        if (!$this->client || !$this->expert) {
            return false;
        }

        return \App\Models\Review::where('expert_id', $this->expert_id)
            ->where('client_id', $this->client_id)
            ->count() > 1;
    }

    /**
     * Check if expert is a recurring expert for this client.
     */
    private function getRecurringExpertStatus(): bool
    {
        if (!$this->client || !$this->expert) {
            return false;
        }

        return \App\Models\Review::where('expert_id', $this->expert_id)
            ->where('client_id', $this->client_id)
            ->count() > 1;
    }
}