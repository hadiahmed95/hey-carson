<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(User::class);
    }

    public function teamPermission()
    {
        return $this->belongsTo(TeamPermission::class);
    }
}
