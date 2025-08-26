<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function expertProfile()
    {
        return $this->belongsTo(Profile::class, 'expert_id', 'user_id');
    }
}
