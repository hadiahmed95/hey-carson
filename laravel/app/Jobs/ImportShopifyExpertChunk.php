<?php

namespace App\Jobs;

use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ImportShopifyExpertChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        foreach ($this->rows as $row) {
            $email = trim($row[4] ?? '');
            if (empty($email) ) {
                continue;
            }

            [$firstName, $lastName] = $this->parseName(trim($row[1] ?? ''));
            $url = trim($row[2]);
            $country = trim($row[6]);

            $user = User::where('email', $email)->first();

            if (!$user) {
                $user = User::create([
                    'email' => $email,
                    'usertype' => 'free',
                    'role_id' => Role::EXPERT,
                    'url' => $url,
                    'phone_number' => $this->cleanPhoneNumber(trim($row[5] ?? '')),
                    'business_address' => $country,
                    'languages' => trim($row[8]),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => Hash::make('12345678'),
                    'new_messages' => 'daily',
                    'project_notifications' => 'instant',
                    'availability_status' => 'available'
                ]);

                $user->profile()->create([
                    'country' => $country,
                    'url' => $url,
                    'role' => "Agency",
                    'experience' => "",
                    'availability' => "",
                    'eng_level' => "",
                    'status' => "inactive",
                    'about' => trim($row[13] ?? '')
                ]);
            }

            $this->processProfileImage($user, $row[0]);
        }
    }

    private function processProfileImage(User $user, string $imageUrl): void
    {
        try {
            $imageUrl = trim($imageUrl);
            if (empty($imageUrl)) return;

            $client = new Client();
            $response = $client->get($imageUrl, [
                'allow_redirects' => true,
                'timeout' => 10,
            ]);

            $type = explode(';', $response->getHeaderLine('Content-Type'))[0];
            if (!str_starts_with($type, 'image/')) return;

            $ext = $this->getExtensionFromMimeType($type);
            $path = "profile-photo/{$user->id}/avatar.$ext";

            Storage::disk('s3')->put($path, (string) $response->getBody());
            $user->update(['photo' => $path]);
        } catch (\Exception $e) {
            \Log::error("❌ Image failed for {$user->email}: " . $e->getMessage());
        }
    }

    private function getExtensionFromMimeType(string $mime): string
    {
        return [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ][$mime] ?? 'jpg';
    }

    private function parseName(string $companyName): array
    {
        $parts = explode(' ', $companyName, 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private function cleanPhoneNumber(string $phone): string
    {
        $phone = rawurldecode($phone);
        $phone = preg_replace('/[^\+\d]/', '', str_replace('tel:', '', $phone));
        return preg_match('/^\+?\d+$/', $phone) ? $phone : '';
    }
}
