<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Symfony\Component\Translation\t;

class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CLAIMED = 'claimed';
    const AVAILABLE = 'available';
    const PENDING_MATCH = 'pending_match';
    const MATCHED = 'matched';
    const PENDING_PAYMENT = 'pending_payment';
    const FOR_REVIEWS = 'for_reviews';
    const IN_PROGRESS = 'in_progress';
    const EXPERT_COMPLETED = 'expert_completed';
    const COMPLETED = 'completed';
    const TAB_CHAT = 'chatroom';
    const TAB_DESCRIPTION = 'description';
    const TAB_INVOICES = 'invoices';

    protected $guarded = [];

    protected $casts = [
        'additional_experts' => 'array',
        'is_additional_experts' => 'boolean',
    ];

    private $isJob = false;

    public function setIsJob(bool $value)
    {
        $this->isJob = $value;
    }

    public function getIsJob()
    {
        return $this->isJob;
    }

    public function projectFiles(): HasMany
    {
        return $this->hasMany(ProjectFiles::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function request(): HasOne
    {
        return $this->hasOne(Request::class, 'project_id');
    }

    public function preferredExpert()
    {
        return $this->belongsTo(User::class, 'preferred_expert_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function invoices()
    {
        return $this->hasMany(Payment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->where('is_active', true);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function experts(): HasManyThrough
    {
        return $this->through('assignments')->has('expert');
    }

    public function activeExpert(): HasOneThrough
    {
        return $this->through('activeAssignment')->has('expert');
    }

    public function history(): HasMany
    {
        return $this->hasMany(ProjectHistory::class);
    }

    /**
     * @return HasMany
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'project_id');
    }
}
