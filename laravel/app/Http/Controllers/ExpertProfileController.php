<?php

namespace App\Http\Controllers;


use App\Models\Profile;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\ExpertFundRepository;
use Illuminate\Support\Facades\Log;

class ExpertProfileController extends Controller
{
    private ExpertFundRepository $expertFundRepository;

    public function __construct(ExpertFundRepository $expertFundRepository)
    {
        $this->expertFundRepository = $expertFundRepository;
    }

    public function fetchAllExperts(Request $request)
    {
        $experts = User::query()
            ->where('role_id', 3)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('usertype', 'paid')
                        ->whereHas('profile', function ($q2) {
                            $q2->where('status', 'active');
                        });
                })->orWhere('usertype', '!=', 'paid');
            })
            ->with([
                'profile',
                'reviews',
                'assignments' => fn ($q) => $q->select('id', 'expert_id', 'total_response_time', 'response_pair_count')
                    ->orderBy('created_at', 'desc'),
                'serviceCategories:id,name'
            ])
            ->orderByRaw("CASE WHEN usertype = 'paid' THEN 0 ELSE 1 END")
            ->orderByRaw('CASE WHEN photo IS NULL THEN 1 ELSE 0 END')
            ->get();

        $experts = $experts->sortByDesc(fn ($expert) => $expert->serviceCategories->count())->values();

        $validExperts = $experts->filter(function ($expert) {
            $fullName = trim($expert->first_name . ' ' . $expert->last_name);
            $slug = Str::slug($fullName);

            $validator = Validator::make(['slug' => $slug], ['slug' => 'required|alpha_dash']);
            $containsSymbols = preg_match('/[^\p{L}\p{N}\s-]/u', $fullName);

            return !$validator->fails() && !$containsSymbols;
        });

        $response = [];
        $nameTracker = [];

        foreach ($validExperts as $expert) {
            $baseName = trim($expert->first_name . ' ' . $expert->last_name);
            if (!isset($nameTracker[strtolower($baseName)])) {
                $nameTracker[strtolower($baseName)] = 1;
                $uniqueName = $baseName;
            } else {
                $uniqueName = $baseName . ' ' . $nameTracker[strtolower($baseName)];
                $nameTracker[strtolower($baseName)]++;
            }
            $username = Str::slug($uniqueName);
            $totalReviews = $expert->reviews->count();
            $averageRating = $totalReviews > 0
                ? round($expert->reviews->avg('rate'), 1)
                : 0;

            $totalResponseTime = $expert->assignments->sum('total_response_time');
            $totalPairs = $expert->assignments->sum('response_pair_count');

            $averageResponseTime = $totalPairs > 0
                ? round(($totalResponseTime / $totalPairs) / 1440, 20)
                : 0;


            $isPaid = $expert->usertype === 'paid';

            $formattedResponseTime = 0;
            if ($isPaid && $totalPairs > 0) {
                $formattedResponseTime = $averageResponseTime;
            }

            $fullName = trim($expert->first_name . ' ' . $expert->last_name);
            $photoUrl = $expert->photo ? env('AWS_URL') . '/' . $expert->photo : null;
            $company = $isPaid ? 'www.shopexperts.com' : $expert->url;
            $phone = $isPaid ? '+1 514-900-6623' : $expert->phone_number;


            $response[] = [
                'Expert Email' => $expert->email,
                'Expert Email Empty State' => !$expert->email,
                'Experts Name' => $uniqueName,
                'Experts Name Empty State' => !$uniqueName,
                'Expert Image Empty State' => !$photoUrl,
                'Expert Image' => $photoUrl,
                'Type of Account Empty State' => false,
                'Type of Account' => $isPaid ? 'Freelancer' : 'Agency',
                'Role Empty State' => !$isPaid,
                'Role' => $isPaid ? 'Senior Shopify Developer' : null,
                'Role Info Empty State' => !$isPaid,
                'Role Info' => $isPaid,
                'Country Empty State' => !$expert->profile?->country,
                'Country' => $expert->profile?->country,
                'Shopexperts since Info Empty State' => !$isPaid,
                'Shopexperts since Info' => $isPaid,
                'shopexperts since Empty State' => !($isPaid && $expert->profile),
                'shopexperts since' => $isPaid && $expert->profile ? $expert->created_at->format('F, Y') : null,
                'Online Indicator Info Empty State' => !$isPaid,
                'Online Indicator Info' => $isPaid,
                'Average response time Info Empty State' => !$isPaid,
                'Average response time Info' => $isPaid,
                'Local Time Info Empty State' => !$isPaid,
                'Local Time Info' => $isPaid,
                'Languages Empty State' => false,
                'Languages' => $expert->languages ?: 'English',

                'Expert-Rating Empty State' => $totalReviews === 0,
                'Expert-Rating' => $totalReviews > 0 ? round($averageRating) : 0,
                'Number_of_reviews' => $totalReviews,
                'Reviews Empty State' => $totalReviews === 0,
                'Customer Reviews' => $totalReviews > 0,

                'Business Address Empty State' => !$isPaid && !$expert->business_address,
                'Business Address' => $isPaid
                    ? "2967 Dundas St. W. #575 Toronto, ON M6P 1Z2 Canada"
                    : $expert->business_address,

                'Local Time Empty State' => false,
                'Local Time' => now()->setTimezone('America/Toronto')->format('D, g:i a'),
                'Starting Price Info Empty State' => !$isPaid,
                'Starting Price Info' => $isPaid,
                'Starting-Price Empty State' => !$expert->profile?->hourly_rate,
                'Starting-Price' => $expert->profile?->hourly_rate
                    ? round((float) $expert->profile->hourly_rate, 2)
                    : null,

                'Company Name Empty State' => !$company,
                'Company Name' => $company,
                'Company URL Empty State' => !$company,
                'Company URL' => $company,
                'Phone number Empty State' => !$phone,
                'Phone number' => $phone,
                'Average response time Empty State' => !$formattedResponseTime,
                'Average response time' => $formattedResponseTime,

                'About Empty State' => $expert->profile?->about,
                'About' => $expert->profile?->about,


                'Paid User Empty State' => !$isPaid,
                'Paid User' => $isPaid,
                'Show Website Empty State' => !$isPaid,
                'Show Website' => $isPaid,
                'Show other links Empty State' => !$isPaid,
                'Show other links' => $isPaid,
                'Show phone number Empty State' => !$isPaid,
                'Show phone number' => $isPaid,
                'Claimed Badge Empty State' => !$isPaid,
                'Claimed Badge' => $isPaid,
                'Claim CTA Empty State' => $isPaid,
                'Claim CTA' => !$isPaid,
                'Claim CTA URL Empty State' => false,
                'Claim CTA URL' => 'claimyourprofile.com',
                'Quote Button Empty State' => false,
                'Quote Button' => true,
                'Chat Button Empty State' => !$isPaid,
                'Chat Button' => $isPaid,

                'LinkedIn Icon Empty State' => !$isPaid,
                'LinkedIn Icon' => $isPaid,
                'LinkedIn Url Empty State' => !$isPaid,
                'LinkedIn Url' => $isPaid ? 'linkedIn.com' : null,
                'X/Twitter Icon Empty State' => !$isPaid,
                'X/Twitter Icon' => $isPaid,
                'X/Twitter Url Empty State' => !$isPaid,
                'X/Twitter Url' => $isPaid ? 'twitter.com' : null,
                'GitHub Icon Empty State' => !$isPaid,
                'GitHub Icon' => $isPaid,
                'GitHub Url Empty State' => !$isPaid,
                'GitHub Url' => $isPaid ? 'Github.com' : null,
                'Fiver Icon Empty State' => !$isPaid,
                'Fiver Icon' => $isPaid,
                'Fiverr Url Empty State' => !$isPaid,
                'Fiverr Url' => $isPaid ? 'fiverr.com' : null,
                'Upwork Icon Empty State' => !$isPaid,
                'Upwork Icon' => $isPaid,
                'Upwork Url Empty State' => !$isPaid,
                'Upwork Url' => $isPaid ? 'upwork.com' : null,

                'Shopexperts Premium Partner Empty State' => !$isPaid,
                'Shopexperts Premium Partner' => $isPaid,
                'Main Service Categories Empty State' => !trim(implode(', ', $expert->serviceCategories->pluck('name')->all())),
                'Main Service Categories' => trim(implode(', ', $expert->serviceCategories->pluck('name')->all())),
                'Sub Categories Empty State' => false,
                'Sub Categories' => 'Shopify Payment Gateway Setup',
                'Packaged Services Empty State' => !$isPaid,
                'Packaged Services' => $isPaid,
                'Customer Stories Empty State' => !$isPaid,
                'Customer Stories' => $isPaid,
                'Has FAQ Empty State' => !$isPaid,
                'Has FAQ' => $isPaid,
            ];
        }

        return response()->json($response);
    }

    public function fetchServiceCategories()
    {
        $categories = ServiceCategory::with('experts')->get();

        return response()->json(
            $categories->map(function ($category) {
                return [
                    'id' => $category->id, // Critical for references
                    'Category Name' => $category->name,
                    'Main Category Slug' => $category->slug,
                    'Main Category Description' => $category->description ?? '',
                    'Experts' => $category->experts->map(fn ($expert) => ['Expert Name' => $expert->first_name . ' ' . $expert->last_name])
                ];
            })
        );
    }

    public function getReviews(Request $request)
    {
        $experts = User::with(['reviews.client', 'profile'])
            ->where('role_id', 3)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('usertype', 'paid')
                        ->whereHas('profile', fn ($q2) => $q2->where('status', 'active'));
                })->orWhere('usertype', '!=', 'paid');
            })
            ->get();

        $validExperts = $experts->filter(function ($expert) {
            $fullName = trim($expert->first_name . ' ' . $expert->last_name);
            $slug = Str::slug($fullName);

            $validator = Validator::make(['slug' => $slug], ['slug' => 'required|alpha_dash']);
            $containsSymbols = preg_match('/[^\p{L}\p{N}\s-]/u', $fullName);

            return !$validator->fails() && !$containsSymbols;
        });

        $response = [];
        $uniqueKeys = [];

        foreach ($validExperts as $expert) {

            $reviews = $expert->reviews->isNotEmpty() ? $expert->reviews : [null];

            foreach ($reviews as $review) {
                $uniqueKey = $review
                    ? "expert_{$expert->id}_client_{$review->client_id}_review_{$review->id}"
                    : "expert_{$expert->id}_no_review";
                if (isset($uniqueKeys[$uniqueKey])) {
                    continue;
                }
                $uniqueKeys[$uniqueKey] = true;
                $hasReview = $review && $review->client;
                $client = $hasReview ? $review->client : null;

                $rating = $hasReview ? floatval($review->rate) : 0;

                $recommendation = match (true) {
                    $rating >= 4.0 => 'Very Likely',
                    $rating >= 2.0 => 'Neutral',
                    $rating >= 1.0 => 'Less Likely',
                    default         => $hasReview ? 'Not Rated' : null,
                };

                $repeatClient = $hasReview
                    ? Review::where('expert_id', $review->expert_id)
                        ->where('client_id', $review->client_id)
                        ->count() > 1
                    : null;

                $response[] = [
                    'Unique Key'          => $uniqueKey,
                    'Expert Name'         => "{$expert->first_name} {$expert->last_name}",
                    'Expert Image'        => $expert->photo ? env('AWS_URL') . '/' . $expert->photo : null,
                    'Client Name'         => $client ? "{$client->first_name} {$client->last_name}" : null,
                    'Client Image'        => $client && $client->photo ? env('AWS_URL') . '/' . $client->photo : null,
                    'Repeated Client'     => $repeatClient,
                    'Hired on Shopexperts'=> $expert->usertype === 'paid',
                    'Company Name'        => $client?->company_name ?? ($hasReview ? 'Check reviewer store' : null),
                    'Client Website'      => $client?->url,
                    'Rating'              => $expert->usertype === 'paid' ? number_format($rating, 2) : null,
                    'Review Content'      => $hasReview ? $review->comment : null,
                    'Posted At'           => $hasReview ? $review->created_at->format('M d, Y') : null,
                    'Project Value'       => $expert->usertype === 'paid' ? '$100–$1000' : null,
                    'Likely to Recommend' => $recommendation,
                    'Review Source'       => $expert->usertype === 'paid' ? 'Organic' : null,
                ];
            }
        }

        return response()->json($response);
    }

    public function fetchCountryExperts()
    {
        $experts = User::with('profile')
            ->whereHas('profile', fn($q) => $q->whereNotNull('country'))
            ->get();

        $response = [];
        $id = 1;

        foreach ($experts as $expert) {
            $location = $expert->profile->country;
            $parts = array_map('trim', explode(',', $location));

            // If "City, Country" → take country from second part
            $countryName = count($parts) === 2 ? $parts[1] : $parts[0];
            $countrySlug = Str::slug($countryName);

            $response[] = [
                'id' => $id++,
                'Country Name' => $countryName,
                'Slug' => $countrySlug,
                'Experts' => [
                    ['Expert Name' => $expert->first_name . ' ' . $expert->last_name]
                ]
            ];
        }

        return response()->json($response);
    }

    public function fetchCityExperts()
    {
        $experts = User::with('profile')
            ->whereHas('profile', fn($q) => $q->whereNotNull('country'))
            ->get();

        $response = [];
        $id = 1;

        foreach ($experts as $expert) {
            $location = $expert->profile->country;
            $parts = array_map('trim', explode(',', $location));

            if (count($parts) !== 2) continue;

            [$cityName, $countryName] = $parts;
            $citySlug = Str::slug($cityName);
            $countrySlug = Str::slug($countryName);

            $response[] = [
                'id' => $id++,
                'City Name' => $cityName,
                'Slug' => $citySlug,
                'Reference Country' => $countryName,
                'Experts' => [
                    ['Expert Name' => $expert->first_name . ' ' . $expert->last_name]
                ]
            ];
        }

        return response()->json($response);
    }


    private function getValueRange($value)
    {
        $ranges = [
            'under_1000' => 'Under $100',
            '100_1000' => '$100 - $1000',
            '1000_5000' => '$1000 - $5000',
            '5000_20000' => '$5000 - $20000',
            '20000_100000' => '$20000 - $100000',
            'over_100000' => 'Over $1,00000'
        ];

        return $ranges[$value] ?? 'Not specified';
    }
}
