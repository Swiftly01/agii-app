<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // Add to the $fillable array in User model
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'user_type',
        'state',
        'local_government',
        'city',
        'address', // Add this
        'business_name',
        'business_type',
        'business_category',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'whatsapp_number',
        'profile_image', // Add this
        'referral_code',
        'referred_by',
        'interests',
        'newsletter_subscribed',
        'terms_accepted',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts()
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'newsletter_subscribed' => 'boolean',
            'terms_accepted' => 'boolean',
            'interests' => 'array',
        ];
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * True for accounts created via password registration (or that have
     * since set one). False for Google-only accounts, which can't use
     * the password login form until they set one.
     */
    public function hasPassword(): bool
    {
        return ! is_null($this->password);
    }

    public function isGoogleAccount(): bool
    {
        return ! is_null($this->google_id);
    }

    // Add subscription relationship
    public function subscriptions()
    {
        return $this->hasMany(VendorSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(VendorSubscription::class)
            ->active()
            ->latest();
    }

    public function getPlanNameAttribute()
    {
        return $this->paymentPlan?->name;
    }

    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class, 'payment_plan_id');
    }

    public function hasActiveSubscription()
    {
        return $this->activeSubscription()->exists();
    }

    public function getCurrentPlanAttribute()
    {
        return $this->activeSubscription?->paymentPlan;
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function stores()
    {
        return $this->hasOne(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function vendorContacts()
    {
        return $this->hasMany(VendorContact::class);
    }

    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function boostSubscriptions()
    {
        return $this->hasMany(BoostSubscription::class);
    }

    public function activeBoostSubscription()
    {
        return $this->boostSubscriptions()->active()->latest('expires_at')->first();
    }

    public function hasActiveBoostSubscription(): bool
    {
        return $this->activeBoostSubscription() !== null;
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'user_id')
            ->where('status', 'active');
    }

    public function hasStore()
    {
        return $this->store()->exists();
    }

    /**
     * Get the current payment plan ID (convenience method)
     */
    public function getPaymentPlanAttribute()
    {
        return $this->activeSubscription?->payment_plan_id;
    }

    /**
     * Check if user has a specific payment plan
     */
    public function hasPaymentPlan($planIds)
    {
        if (!is_array($planIds)) {
            $planIds = [$planIds];
        }

        $activeSub = $this->activeSubscription;
        return $activeSub && in_array($activeSub->payment_plan_id, $planIds);
    }

    /**
     * Check if user is on a store plan (plans 4,5,6)
     */
    public function isOnStorePlan()
    {
        return $this->hasPaymentPlan([4, 5, 6]);
    }

    /**
     * Check if user is on a regular plan (plans 1,2,3)
     */
    public function isOnRegularPlan()
    {
        return $this->hasPaymentPlan([1, 2, 3]);
    }

    public function canAddMoreProducts()
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        $plan = $this->current_plan;

        // If plan has unlimited products (NULL product_limit)
        if (is_null($plan->product_limit)) {
            return true;
        }

        return $this->products()->count() < $plan->product_limit;
    }

    public function getProductLimit()
    {
        if (!$this->hasActiveSubscription()) {
            return 0;
        }

        return $this->current_plan->product_limit;
    }

    /**
     * Check if user has unlimited products
     */
    public function hasUnlimitedProducts()
    {
        if (!$this->hasActiveSubscription()) {
            return false;
        }

        return is_null($this->current_plan->product_limit);
    }

    public function hasExpiredSubscription()
    {
        return !$this->hasActiveSubscription();
    }

    // Add to your User model

    /**
     * Check if user can create a store (plans 4,5,6 with active subscription)
     */
    public function canCreateStore()
    {
        // Must be vendor, have active subscription, and be on store plans
        if ($this->user_type !== 'vendor' || !$this->hasActiveSubscription()) {
            return false;
        }

        // Check if on store plans (4,5,6)
        if (!$this->isOnStorePlan()) {
            return false;
        }

        // Check store limit
        $storeLimit = $this->current_plan->store_limit ?? 1;
        return $this->stores()->count() < $storeLimit;
    }

    /**
     * Get available store slots
     */
    public function getAvailableStoreSlots()
    {
        if (!$this->hasActiveSubscription() || !$this->isOnStorePlan()) {
            return 0;
        }

        $storeLimit = $this->current_plan->store_limit ?? 1;
        $currentStores = $this->stores()->count();

        return max(0, $storeLimit - $currentStores);
    }

    /**
     * Check if user can add more stores
     */
    public function canAddMoreStores()
    {
        return $this->getAvailableStoreSlots() > 0;
    }

    /**
     * Get store plan features
     */
    public function getStorePlanFeatures()
    {
        if (!$this->hasActiveSubscription() || !$this->isOnStorePlan()) {
            return null;
        }

        $plan = $this->current_plan;

        return [
            'store_limit' => $plan->store_limit ?? 1,
            'product_limit' => $plan->product_limit,
            'has_unlimited_products' => is_null($plan->product_limit),
            'plan_name' => $plan->name,
            'plan_id' => $plan->id,
        ];
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'local_government', 'lga');
    }


    // app/Models/User.php

    // Add to the User model
    public function marketer()
    {
        return $this->hasOne(Marketer::class);
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function marketerVendors()
    {
        return $this->hasMany(User::class, 'referred_by')->where('user_type', 'vendor');
    }

    public function isMarketer()
    {
        return $this->user_type === 'marketer';
    }

    public function isVendor()
    {
        return $this->user_type === 'vendor';
    }

    public function isCustomer()
    {
        return $this->user_type === 'customer';
    }
    
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }



    /**
     * Check if user is an active marketer.
     */
    public function isActiveMarketer(): bool
    {
        return $this->isMarketer() && $this->marketer->is_active;
    }

    /**
     * Get vendors referred by this user (if marketer).
     */
    public function referredVendors()
    {
        if (!$this->isMarketer()) {
            return collect();
        }

        return $this->marketer->referredVendors;
    }

    /**
     * Get commission transactions (if marketer).
     */
    public function commissionTransactions()
    {
        if (!$this->isMarketer()) {
            return collect();
        }

        return $this->marketer->commissionTransactions;
    }

    /**
     * Get marketer statistics.
     */
    public function getMarketerStats(): array
    {
        if (!$this->isMarketer()) {
            return [];
        }

        return $this->marketer->getPerformanceStats();
    }
    
        // app/Models/User.php
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participants');
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
      public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'marketer_id');
    }

    /**
     * Get tasks created by this user (as assigner).
     */
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    /**
     * Get pending tasks assigned to this user.
     */
    public function pendingTasks()
    {
        return $this->assignedTasks()->where('status', 'pending');
    }

    /**
     * Get in-progress tasks assigned to this user.
     */
    public function inProgressTasks()
    {
        return $this->assignedTasks()->where('status', 'in_progress');
    }

    /**
     * Get completed tasks assigned to this user.
     */
    public function completedTasks()
    {
        return $this->assignedTasks()->where('status', 'completed');
    }
    
    
    // User.php
    public function marketercommissionTransactions()
    {
        return $this->hasManyThrough(
            CommissionTransaction::class,
            Marketer::class,
            'user_id',       // Foreign key on marketers table
            'marketer_id',   // Foreign key on commission_transactions table
            'id',            // Local key on users table
            'id'             // Local key on marketers table
        );
    }
    
    // app/Models/User.php
    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }
  

    /**
     * Check if user is a marketer.
     */
    // public function isMarketer(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => $this->user_type === 'marketer'
    //     );
    // }
}