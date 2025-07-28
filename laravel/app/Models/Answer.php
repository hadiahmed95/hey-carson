<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function question(): HasOne
    {
        return $this->hasOne(Question::class, 'question_id');
    }
}
