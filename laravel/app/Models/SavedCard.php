<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavedCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = ['payment_id', 'customer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
