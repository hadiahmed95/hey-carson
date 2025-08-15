<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\ExpertFundRepository;

/**
 * Class ExpertResource
 * 
 * Resource class for transforming expert data with business logic calculations
 * and presentation formatting including all default value handling.
 * 
 * @package App\Http\Resources
 */
class ExpertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * Returns fully formatted data with all defaults applied, ready for frontend consumption.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expertFundRepository = app(ExpertFundRepository::class);
        
        // Business logic calculations
        $totalEarnings = $expertFundRepository->getTotalEarnings($this->id);
        $servicesOffered = $this->serviceCategories ? $this->serviceCategories->pluck('name')->toArray() : [];
        $stats = [
            'total_reviews' => $this->reviews ? $this->reviews->count() : 0,
            'average_rating' => $this->reviews && $this->reviews->count() > 0 ? 
                round($this->reviews->avg('rate'), 1) : 0,
            'total_projects' => $this->activeAssignments ? $this->activeAssignments->count() : 0,
        ];
        
        // Null-safe profile access
        $profile = $this->profile;
        
        // Presentation formatting with all defaults and null checks
        $displayName = $this->first_name . ' ' . $this->last_name;
        $hasRealPhoto = $this->photo && $this->photo !== null && $this->photo !== '';
        $hourlyRate = $profile ? ($profile->hourly_rate ?? 0) : 0;
        $formattedHourlyRate = '$' . number_format($hourlyRate, 2);
        $expertStatus = $profile && $profile->status 
            ? ucfirst($profile->status)
            : 'Pending';
        $statusUpdatedAt = $this->updated_at 
            ? $this->updated_at->format('j M, Y')
            : now()->format('j M, Y');
        
        return [
            // Essential data for frontend
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'photo' => $this->photo,
            'url' => $this->url ?: 'https://www.trustpilot.com/', // Default URL
            'updated_at' => $this->updated_at,
            'profile' => $this->whenLoaded('profile'),
            
            // Business logic (calculated data)
            'totalEarnings' => $totalEarnings,
            'services_offered' => $servicesOffered,
            'stats' => $stats,
            
            // Presentation formatting (ready for UI with all defaults and null safety)
            'display_name' => $displayName,
            'has_real_photo' => $hasRealPhoto,
            'avatar_url' => $hasRealPhoto ? asset('storage/' . $this->photo) : null,
            'expert_type_formatted' => $profile && $profile->expert_type 
                ? ucfirst($profile->expert_type) 
                : 'N/A', // Default type
            'hourly_rate_formatted' => $formattedHourlyRate,
            'status_formatted' => $expertStatus,
            'status_updated_at_formatted' => $statusUpdatedAt,
            'country_formatted' => $profile && $profile->country ? $profile->country : 'N/A', // Default country
            'job_title_formatted' => $profile && $profile->role ? $profile->role : 'N/A', // Default job title
            'language_formatted' => $profile && $profile->eng_level ? $profile->eng_level : 'N/A', // Default language
            'store_title' => 'Check Website', // Default store title
            'services_offered_safe' => $servicesOffered, // Already defaulted to []
            'total_reviews_safe' => $stats['total_reviews'], // Already defaulted to 0
            'average_rating_safe' => $stats['average_rating'], // Already defaulted to 0
        ];
    }
}