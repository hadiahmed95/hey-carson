<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertStat extends Model
{
    use HasFactory;

    protected $table = 'experts_stats';
    protected $guarded = [];
}
