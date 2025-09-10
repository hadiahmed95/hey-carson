<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackagedService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'packaged_services';
    protected $guarded = [];

    protected $fillable = [
       'id','expert_id','title','description','price','delivery_time','thumbnail',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
    ];
}
