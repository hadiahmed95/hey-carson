<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopifyProductUpdate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shopify_product_updates';
    protected $guarded = [];
}
