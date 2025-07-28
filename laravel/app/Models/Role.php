<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ADMIN = 1;
    const CLIENT = 2;
    const EXPERT = 3;
    const MEMBER = 4;

    protected $guarded = [];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
