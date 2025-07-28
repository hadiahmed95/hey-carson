<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Logs
{
    public function inboundEmailLogs(string $date): mixed
    {
        $filePath = storage_path("logs/inboundEmails/inbound_emails-$date");

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => "Log file for date $date not found."
            ], 404);
        }

        return response()->download($filePath, "inbound_emails-$date.log");
    }
}
