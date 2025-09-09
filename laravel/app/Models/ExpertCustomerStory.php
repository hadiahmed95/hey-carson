<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertCustomerStory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'expert_customer_stories';

    protected $fillable = [
        'expert_id',
        'title',
        'images',
        'problem',
        'solution',
        'result',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}
