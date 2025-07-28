<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceCategory extends Model
{
    use HasFactory;
    protected $table = 'service_categories';

    protected $guarded = [];

    /**
     * The experts (users) that belong to this service category.
     */
    public function experts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'expert_service_categories', 'category_id', 'user_id');
    }
}
