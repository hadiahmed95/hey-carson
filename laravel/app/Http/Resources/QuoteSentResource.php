<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteSentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'hours' => $this->hours,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
            'rate' => $this->rate,
            'project_name' => $this->project_name,
            'project_url' => $this->project_url,
            'payment_status' => $this->formatPaymentStatus($this->payment_status),
            'client_name' => $this->client_name,
            'client_url' => $this->client_url,
            'client_shopify_plan' => $this->client_shopify_plan,
            'client_photo' => $this->client_photo,
            'expert_name' => $this->expert_name,
            'expert_url' => $this->expert_url,
            'expert_type' => $this->expert_type,
            'expert_photo' => $this->expert_photo,
        ];
    }

    /**
     * Format payment status for display
     */
    public static function formatPaymentStatus($status)
    {
        if (!$status) {
            return 'Pending Payment';
        }
        
        return match(strtolower($status)) {
            'send' => 'Pending Payment',
            'paid' => 'Paid',
            'rejected', 'declined' => 'Rejected',
            'expired' => 'Expired',
            default => ucfirst($status)
        };
    }
}