<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertOfferedService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'expert_offered_services';

    protected $fillable = [
        'expert_id',
        'category_id', 'category_name',
        'subservice1_id', 'subservice1_name',
        'subservice2_id', 'subservice2_name',
        'subservice3_id', 'subservice3_name',
    ];
}
