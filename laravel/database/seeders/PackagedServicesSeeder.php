<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackagedServicesSeeder extends Seeder
{
    public function run(): void
    {
        $expertIds = [25017, 25018, 25019, 25020, 25021];

        $sampleDescriptions = [
            'Get a fully responsive Shopify store designed from scratch with modern UX principles.',
            'I will optimize your store speed, SEO, and mobile performance to improve conversion rates.',
            'Need custom product pages? I create tailor-made layouts with dynamic content sections.',
            'This package includes theme customization, homepage redesign, and collection setup.',
            'Migrate your existing store to Shopify with zero downtime and pixel-perfect precision.',
            'Build a Shopify landing page that converts — designed with A/B testing in mind.',
            'Implement custom Shopify scripts and metafields for a seamless checkout experience.',
            'Enhance your Shopify store with smart filters, sorting, and advanced product tagging.',
            'End-to-end Shopify Plus implementation for high-volume stores with scalability in mind.',
            'UX/UI design improvements for mobile and desktop — focused on usability and aesthetics.',
        ];

        $thumbnails = [
            'https://plus.unsplash.com/premium_photo-1677487978412-1e27f45f3e0a?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.1.0',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1470&q=80',
            'https://images.unsplash.com/photo-1522199710521-72d69614c702?auto=format&fit=crop&w=1470&q=80',
        ];

        $featuredIndices = collect(range(0, 49))->shuffle()->take(10)->toArray();

        for ($i = 0; $i < 50; $i++) {
            DB::table('packaged_services')->insert([
                'expert_id'     => $expertIds[array_rand($expertIds)],
                'title'         => 'Shopify Service Package ' . ($i + 1),
                'description'   => $sampleDescriptions[array_rand($sampleDescriptions)],
                'price'         => mt_rand(100, 1000),
                'delivery_time' => mt_rand(2, 3) . '-' . mt_rand(4, 6) . ' business days',
                'thumbnail'     => $thumbnails[array_rand($thumbnails)],
                'is_featured'   => in_array($i, $featuredIndices) ? 1 : 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
