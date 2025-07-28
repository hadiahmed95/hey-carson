<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CLIENT_PROJECT_MATCHED = 1;
    const CLIENT_PROJECT_OFFER = 2;
    const CLIENT_PROJECT_SCOPE = 3;
    const CLIENT_PROJECT_PAYMENT = 4;
    const CLIENT_PROJECT_COMPLETE = 5;
    const EXPERT_PROJECT_MATCHED = 6;
    const EXPERT_PROJECT_AVAILABLE = 7;
    const EXPERT_PROJECT_PAYMENT_OFFER = 8;
    const EXPERT_PROJECT_PAYMENT_SCOPE = 9;
    const EXPERT_PROJECT_COMPLETED = 10;
    const EXPERT_PROJECT_FINISHED = 11;
    const EXPERT_PROJECT_AVAILABLE_AGAIN = 12;

    protected $guarded = [];

    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }
}
