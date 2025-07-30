<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ImportShopifyExpertChunk;

class ImportShopifyExpertsFromSheet extends Command
{
    protected $signature = 'import:shopify-experts';
    protected $description = 'Dispatch chunked jobs to import Shopify Experts from CSV';

    public function handle()
    {
        $filePath = storage_path('app/imports/shopify_experts.csv');

        if (!file_exists($filePath)) {
            $this->error("❌ File not found at $filePath");
            return 1;
        }

        $file = fopen($filePath, 'r');
        fgetcsv($file); // skip header

        $chunkSize = 50;
        $chunk = [];
        $dispatched = 0;

        while (($row = fgetcsv($file)) !== false) {
            $chunk[] = $row;

            if (count($chunk) === $chunkSize) {
                ImportShopifyExpertChunk::dispatch($chunk);
                $dispatched++;
                $chunk = [];
            }
        }

        if (!empty($chunk)) {
            ImportShopifyExpertChunk::dispatch($chunk);
            $dispatched++;
        }

        fclose($file);

        $this->info("📦 Dispatched $dispatched jobs with chunk size of $chunkSize.");
        return 0;
    }
}
