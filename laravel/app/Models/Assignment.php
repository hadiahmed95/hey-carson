<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id')->withTrashed();
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
