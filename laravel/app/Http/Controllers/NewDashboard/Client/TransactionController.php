<?php

namespace App\Http\Controllers\NewDashboard\Client;

use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function transactions(): JsonResponse
    {
        $invoices = $this->getClientInvoices();

        return response()->json([
            'transactions' => $invoices
        ]);
    }

    /**
     * @return Collection|array
     */
    public function getClientInvoices(): Collection|array
    {
        return Payment::query()
            ->where('user_id', \Auth::id())
            ->with([
                'project',
                'user.profile',
            ])
            ->latest()
            ->get();
    }

}
