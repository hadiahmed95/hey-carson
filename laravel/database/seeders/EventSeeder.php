<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->truncate();


        Event::insert([
            [
                'title' => "You've been matched with an expert.",
                'message' => 'We found the ideal expert for your project - :expertName',
                'type' => 'client-project-matched'
            ],
            [
                'title' => "You've received a quote for your project",
                'message' => 'A project quote for :projectTitle is waiting for your action to get started.',
                'type' => 'client-project-offer'
            ],
            [
                'title' => "You've received a quote for add-on work",
                'message' => ':expertName has created an add-on offer related to: :projectTitle',
                'type' => 'client-project-scope'
            ],
            [
                'title' => 'Your payment was successful',
                'message' => 'Thank you for your project payment. Expect your expert to get started on your project soon.',
                'type' => 'client-project-payment'
            ],
            [
                'title' => 'Your project has been marked as completed by the expert!',
                'message' => ':expertName has marked your project :projectTitle as completed.',
                'type' => 'client-project-complete'
            ],

            [
                'title' => 'A new project request has been assigned to you',
                'message' => "Great news! We've assigned a new request to you :projectTitle",
                'type' => 'expert-project-matched'
            ],
            [
                'title' => 'A new request is available!',
                'message' => 'A new request is available to claim.',
                'type' => 'expert-project-available'
            ],
            [
                'title' => 'A payment has been made!',
                'message' => ':clientName has made a payment for the project: :projectTitle',
                'type' => 'expert-project-payment-offer'
            ],
            [
                'title' => 'An add-on payment has been made!',
                'message' => ':clientName has made an add-on payment for the project: :projectTitle',
                'type' => 'expert-project-payment-scope'
            ],
            [
                'title' => "You've marked the project as completed",
                'message' => "You've marked the project with :clientName as completed.",
                'type' => 'expert-project-completed'
            ],
            [
                'title' => 'Your request for final review has been submitted',
                'message' => ':clientName has confirmed the project as completed or it’s been more than 72 hours and the system automatically closed the project.',
                'type' => 'expert-project-finished'
            ],
        ]);
    }
}
