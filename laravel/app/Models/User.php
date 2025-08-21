<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'click_id',
        'partner_id',
        'program_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'url',
        'password_changed',
        'photo',
        'project_notifications',
        'new_messages',
        'is_disable',
        'timezone',
        'company_type',
        'shopify_plan',
        'usertype',
        'source',
        'availability_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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

    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Lead::class, 'client_id');
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isClient()
    {
        return $this->role->name === 'client';
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'expert_service_categories', 'user_id', 'category_id');
    }

    public function generateUserSlug()
    {
        return Str::slug($this->first_name . ' ' . $this->last_name);
    }

    public function isExpert()
    {
        return $this->role->name === 'expert';
    }

    /**
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'expert_lead', 'expert_id', 'lead_id');
    }

    /**
     * Get the direct message requests for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function directMessages()
    {
        return $this->hasMany(Request::class, 'client_id');
    }

    /**
     * Get the quote requests for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quoteRequests()
    {
        return $this->hasMany(Request::class, 'client_id');
    }

    /**
     * Get the paid payments for this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paidPayments()
    {
        return $this->hasMany(Payment::class, 'user_id')->where('status', 'paid');
    }
}
