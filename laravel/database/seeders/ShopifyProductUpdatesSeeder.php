<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShopifyProductUpdatesSeeder extends Seeder
{
    public function run(): void
    {
        $updates = [
            [
                'title' => 'Shop available in French, German and Spanish',
                'category' => 'Shop',
                'description' => 'Shop app and Shop web are available in French, German, and Spanish.',
                'published_at' => Carbon::create(2025, 12, 17),
            ],
            [
                'title' => 'Logo & Background Media on Customer Display',
                'category' => 'POS',
                'description' => 'Customize the Idle Screen by uploading a logo and background image via Display Editor in your Shopify Admin.',
                'published_at' => Carbon::create(2025, 12, 12),
            ],
            [
                'title' => 'New Analytics Dashboard for Orders',
                'category' => 'Analytics',
                'description' => 'Track order trends more efficiently with our new, visually enhanced analytics dashboard.',
                'published_at' => Carbon::create(2025, 11, 28),
            ],
            [
                'title' => 'Enhanced Discounts API Now Available',
                'category' => 'API',
                'description' => 'Create, retrieve, and manage discount codes with more flexibility using the new Discounts API endpoints.',
                'published_at' => Carbon::create(2025, 11, 20),
            ],
            [
                'title' => 'Bulk Editor Improvements for Product Listings',
                'category' => 'Admin',
                'description' => 'Enjoy a faster and cleaner experience editing product details in bulk directly in your admin.',
                'published_at' => Carbon::create(2025, 11, 18),
            ],
            [
                'title' => 'Shopify Payments Supports More Countries',
                'category' => 'Payments',
                'description' => 'Sellers in Norway, Poland, and South Korea can now use Shopify Payments for seamless transactions.',
                'published_at' => Carbon::create(2025, 10, 30),
            ],
            [
                'title' => 'Product Bundles Feature Released',
                'category' => 'Products',
                'description' => 'Create and sell product bundles natively without third-party apps.',
                'published_at' => Carbon::create(2025, 10, 25),
            ],
            [
                'title' => 'New Checkout Branding Options',
                'category' => 'Checkout',
                'description' => 'Customize fonts, colors, and logos in your checkout without editing code.',
                'published_at' => Carbon::create(2025, 10, 12),
            ],
            [
                'title' => 'Order Printer App Redesigned',
                'category' => 'Apps',
                'description' => 'Generate and print receipts and packing slips with our newly redesigned Order Printer app.',
                'published_at' => Carbon::create(2025, 9, 29),
            ],
            [
                'title' => 'New App Store Experience',
                'category' => 'Apps',
                'description' => 'Discover, evaluate, and install apps with a streamlined interface and curated collections.',
                'published_at' => Carbon::create(2025, 9, 18),
            ],
        ];

        foreach ($updates as $update) {
            DB::table('shopify_product_updates')->insert([
                ...$update,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
