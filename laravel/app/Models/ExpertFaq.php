<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertFaq extends Model
{
    use HasTimestamps;
    use SoftDeletes;

    protected $table = 'expert_faqs';
    
    protected $fillable = [
       'expert_id',
       'question',
       'answer',
    ];
}
