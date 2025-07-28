<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigratedMerchant extends Model
{
    use HasFactory;

    protected $table = 'migrated_merchants';

    public function store()
    {
        return $this->hasOne(MigratedStore::class, 'user_id');
    }
}
