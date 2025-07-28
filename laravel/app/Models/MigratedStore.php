<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigratedStore extends Model
{
    use HasFactory;

    protected $table = 'migrated_stores';
}
