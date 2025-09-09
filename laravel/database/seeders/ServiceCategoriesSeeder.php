<?php

// database/seeders/ServiceCategoriesSeeder.php
namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Shopify Store Setup & Management',
            'Shopify Development & Troubleshooting',
            'Shopify Marketing & Sales',
            'Shopify Branding & Design',
            'Shopify Copywriting & Content',
            'Shopify Consulting & Strategy',
            'Shopify Accounting & Financial Services',
            'Shopify AI & Tech Solutions',
            'Shopify Operations & Management',
            'Shopify Technical Support & Maintenance',
            'Shopify Training & Support'
        ];

        foreach ($categories as $category) {
            ServiceCategory::create([
                'name' => $category,
                'slug' => \Illuminate\Support\Str::slug($category)
            ]);
        }
    }
}
