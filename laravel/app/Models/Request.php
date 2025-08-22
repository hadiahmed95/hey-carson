<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'requests';
    public const QUOTE_REQUEST = 'Quote Request';
    public const MATCHED = 'Matched';

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * @return BelongsTo
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
