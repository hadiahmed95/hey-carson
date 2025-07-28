<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    const INFO_EXPERT_MATCHED       = 1;
    const SUCCESS_CLIENT_OFFER      = 2;
    const SUCCESS_CLIENT_SCOPE      = 3;
    const INFO_TEAM_ADDED           = 4;
    const INFO_EXPERT_COMPLETED     = 5;
    const CRITICAL_CLIENT_COMPLETED = 6;
    const SUCCESS_CLIENT_COMPLETED  = 7;

    protected $guarded = [];

    public function messages()
    {
        $this->hasMany(Message::class);
    }
}
