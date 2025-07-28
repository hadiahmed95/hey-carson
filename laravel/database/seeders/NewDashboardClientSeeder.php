<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Request;
use App\Models\Review;
use App\Models\ReviewRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NewDashboardClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update 1 client user
        $client = User::updateOrCreate(
            ['email' => 'client.user@new.com'],
            [
                'role_id' => 2,
                'usertype' => 'paid',
                'first_name' => 'Client',
                'last_name' => 'User',
                'url' => 'client.example.com',
                'company_type' => 'Shopify app',
                'photo' => 'https://randomuser.me/api/portraits/men/1.jpg',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create 5 expert users with profiles
        $hourly_rate = 60.0;
        $experts = collect();
        for ($i = 1; $i <= 5; $i++) {
            $expert = User::updateOrCreate(
                ['email' => "expert{$i}@new.com"],
                [
                    'role_id' => 3,
                    'usertype' => 'paid',
                    'first_name' => 'Expert',
                    'last_name' => 'No' . $i,
                    'url' => "expert{$i}.new.com",
                    'company_type' => 'Freelancer',
                    'is_featured_expert' => 1,
                    'photo' => 'https://randomuser.me/api/portraits/men/' . $i . '0.jpg',
                    'password' => Hash::make('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Profile::updateOrCreate(
                ['user_id' => $expert->id],
                [
                    'country' => 'NewUser123',
                    'url' => 'www.url.com',
                    'about' => 'Experienced expert',
                    'role' => 'Senior Frontend Developer',
                    'experience' => '3-5 years',
                    'availability' => '30-40 hours per week',
                    'eng_level' => 'Fluent',
                    'hourly_rate' => $hourly_rate,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $hourly_rate += 10.0;
            $experts->push($expert);
        }

        $projects = collect();

        $projects->push(Project::updateOrCreate(
            ['name' => 'Test Project 1', 'client_id' => $client->id],
            [
                'click_id' => (string) Str::uuid(),
                'preferred_expert_id' => $experts[0]->id,
                'status' => 'In Progress',
                'url' => 'client.example.com',
                'description' => 'test description',
                'company_type' => 'Shopify app',
                'urgent' => false,
                'status_updated_at' => now(),
                'is_additional_experts' => false,
                'additional_experts' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));

        $projects->push(Project::updateOrCreate(
            ['name' => 'Test Project 2', 'client_id' => $client->id],
            [
                'click_id' => (string) Str::uuid(),
                'preferred_expert_id' => $experts[1]->id,
                'status' => 'In Progress',
                'url' => 'client.example.com',
                'description' => 'test description 2',
                'company_type' => 'Shopify app',
                'urgent' => true,
                'status_updated_at' => now(),
                'is_additional_experts' => true,
                'additional_experts' => [$experts[2]->id, $experts[3]->id, $experts[4]->id],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));

        $projects->push(Project::updateOrCreate(
            ['name' => 'Test Project 3', 'client_id' => $client->id],
            [
                'click_id' => (string) Str::uuid(),
                'preferred_expert_id' => $experts[2]->id,
                'status' => 'In Progress',
                'url' => 'client.example.com',
                'description' => 'test description 3',
                'company_type' => 'Shopify app',
                'urgent' => true,
                'status_updated_at' => now(),
                'is_additional_experts' => false,
                'additional_experts' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));

        $projects->push(Project::updateOrCreate(
            ['name' => 'Test Project 4', 'client_id' => $client->id],
            [
                'click_id' => (string) Str::uuid(),
                'preferred_expert_id' => $experts[3]->id,
                'status' => 'In Progress',
                'url' => 'client.example.com',
                'description' => 'test description 4',
                'company_type' => 'Shopify app',
                'urgent' => true,
                'status_updated_at' => now(),
                'is_additional_experts' => false,
                'additional_experts' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));

        $projects->push(Project::updateOrCreate(
            ['name' => 'Test Project 5', 'client_id' => $client->id],
            [
                'click_id' => (string) Str::uuid(),
                'preferred_expert_id' => $experts[1]->id,
                'status' => 'In Progress',
                'url' => 'client.example.com',
                'description' => 'test description 5',
                'company_type' => 'Shopify app',
                'urgent' => true,
                'status_updated_at' => now(),
                'is_additional_experts' => false,
                'additional_experts' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ));

        // Create 3 requests, use firstOrCreate to avoid duplicates
        Request::firstOrCreate(
            [
                'client_id' => $client->id,
                'type' => 'Matched',
                'expert_id' => $experts[0]->id,
                'project_id' => $projects[0]->id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Request::firstOrCreate(
            [
                'client_id' => $client->id,
                'type' => 'Direct Message',
                'expert_id' => $experts[2]->id,
                'project_id' => null,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        Request::firstOrCreate(
            [
                'client_id' => $client->id,
                'type' => 'Quote Request',
                'expert_id' => $experts[1]->id,
                'project_id' => $projects[1]->id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create 20 reviews per expert if they don't already exist
        foreach ($experts as $index => $expert) {
            for ($i = 1; $i <= 20; $i++) {
                Review::firstOrCreate(
                    [
                        'expert_id' => $expert->id,
                        'client_id' => $client->id,
                        'project_id' => ($index * 20) + $i,
                        'rate' => rand(3, 5),
                    ],
                    [
                        'comment' => 'Great work delivered!, project id -> ' . (($index * 20) + $i),
                        'is_edited' => false,
                        'review_source' => 'Organic',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // Create 1 ReviewRequest per expert if not exists
        foreach ($experts as $index => $expert) {
            ReviewRequest::firstOrCreate(
                [
                    'expert_id' => $expert->id,
                    'project_id' => $projects[$index]->id,
                ],
                [
                    'client_full_name' => $client->first_name . ' ' . $client->last_name,
                    'client_company_name' => 'Client Company',
                    'client_company_website' => 'client.example.com',
                    'hired_on_shopexperts' => true,
                    'repeated_client' => false,
                    'is_client_reviewed' => false,
                    'project_value_range' => '$1000 - $5000',
                    'message' => 'Please share your feedback on the recent project.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
