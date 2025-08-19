<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Review;

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
                // Add missing fields for frontend compatibility
                'rating' => $this->rate,
                'comment' => $this->comment,
                'recommendation' => $this->formatRecommendation($this->recommendation),
                'quality' => $this->quality,
                'communication' => $this->communication,
                'timeToStart' => $this->timeToStart,
                'valueForMoney' => $this->valueForMoney,
                'valueRange' => $this->valueRange,
            ],
            'expert' => [
                'id' => $this->expert?->id,
                'name' => $this->expert ? $this->expert->first_name . ' ' . $this->expert->last_name : null,
                'photo' => $this->expert?->photo,
                'company_type' => $this->expert?->profile?->expert_type ?? 'Agency',
                'isShopexpertUser' => $this->expert?->usertype === 'paid',
                'rank' => $this->expert?->profile?->role ?? 'Senior',
                'storeUrl' => $this->expert?->url ?? '#',
                'storeTitle' => $this->expert?->url ?? 'No Store',
            ],
            // Add missing fields for frontend compatibility
            'projectId' => $this->project_id,
            'projectTitle' => $this->project?->name,
            'response' => $this->response ?? '',
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
        
        // Handle special case for "under_100"
        if ($projectValue === 'under_100') {
            return 'Under $100';
        }
        
        // Handle formats like "100_1000" or "1000_5000"
        if (strpos($projectValue, '_') !== false) {
            $parts = explode('_', $projectValue);
            if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                $min = (float)$parts[0];
                $max = (float)$parts[1];
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

        return Review::where('expert_id', $this->expert_id)
            ->where('client_id', $this->client_id)
            ->count() > 1;
    }

    /**
     * Format status for display
     */
    public static function formatStatus($status)
    {
        switch($status) {
            case 'pending': return 'Pending Approval';
            case 'approved': return 'Published';
            case 'rejected': return 'Rejected';
            case 'hidden': return 'Hidden';
            default: return ucfirst($status);
        }
    }

    /**
     * Format rating for display
     */
    public static function formatRating($rating)
    {
        return $rating . ' Star' . ($rating != 1 ? 's' : '');
    }
}