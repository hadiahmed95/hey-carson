<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    public function reply()
    {
        return $this->belongsTo(self::class, 'reply_id');
    }

    public function projectFile()
    {
        return $this->hasMany(ProjectFiles::class);
    }
}
