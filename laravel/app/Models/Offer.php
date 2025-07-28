<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function expert()
    {
        return $this->belongsTo(User::class);
    }
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
