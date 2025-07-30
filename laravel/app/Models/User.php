<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function memeber()
    {
        return $this->hasOne(Team::class, 'memeber_id');
    }

    public function userEvents()
    {
        return $this->hasMany(UserEvent::class);
    }

    public function teamOwner()
    {
        return $this->hasOne(Team::class, 'owner_id');
    }

    public function teamPermissions()
    {
        return $this->hasMany(TeamPermission::class, 'owner_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'expert_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class, 'expert_id');
    }

    public function clientReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'expert_id');
    }

    public function assignedProjects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, Assignment::class, 'expert_id', 'id', 'project_id', 'id' );
    }

    public function activeAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'expert_id')->where('is_active', true);
    }

    public function savedCards()
    {
        return $this->hasMany(SavedCard::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'expert_id');
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isClient()
    {
        return $this->role->name === 'client';
    }

    public function isExpert()
    {
        return $this->role->name === 'expert';
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'expert_service_categories', 'user_id', 'category_id');
    }

    public function getDisplayNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http')) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    public function getAccountTypeAttribute()
    {
        return 'freelancer';
    }

    public function getCompanyTypeDisplayAttribute()
    {
        return 'Individual';
    }

    public function getHourlyRateFormattedAttribute()
    {
        $rate = $this->profile?->hourly_rate ?? 0;
        return '' . number_format($rate, 2) . '/hour';
    }

    public function getStatsAttribute()
    {
        return [
            'total_reviews' => $this->reviews()->count(),
            'average_rating' => $this->reviews()->avg('rate') ?? 0,  // ✅ Correct column name
            'total_projects' => $this->activeAssignments()->count(),
            'response_time' => '2 hours',
            'success_rate' => '95%',
        ];
    }

    public function scopeForV2Listing($query)
    {
        return $query->with([
            'profile',
            'reviews',
            'activeAssignments.project'
        ]);
    }

    public function scopeByRole($query, $role)
    {
        return $query->whereHas('profile', function($q) use ($role) {
            $q->where('role', $role);
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->whereHas('profile', function($q) use ($status) {
            $q->where('status', $status);
        });
    }
}
