<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\Chatable;
use App\Support\SellerContact;
use Illuminate\Support\Str;

class Product extends Model implements Chatable
{
    use HasFactory;

    // Cover shown for listings that only have a video
    public const VIDEO_PLACEHOLDER = 'images/video-placeholder.svg';

    protected $fillable = [
        'user_id',
        'store_id',
        'category_id',
        'title',
        'slug',
        'description',
        'price',
        'old_price',
        'condition',
        'location',
        'quantity',
        'images',
        'videos',
        'specifications',
        'rating',
        'review_count',
        'status',
        'featured',
        'boost_expires_at',
        'is_boost_carousel_pick',
        'negotiable',
        'tags',
        'meta_title',
        'meta_description',
        'views',
        'reviewed_at',
        'reviewed_by',
        'rejection_reason'
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'specifications' => 'array',
        'tags' => 'array',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'featured' => 'boolean',
        'negotiable' => 'boolean',
        'rating' => 'decimal:1',
        'views' => 'integer',
        'boost_expires_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'buyers_notified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // reviewed_at/reviewed_by/rejection_reason are intentionally NOT in
    // $fillable — only ProductApprovalService should set them.
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendorContacts()
    {
        return $this->hasMany(VendorContact::class);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * "Rejected" isn't a separate status value — it's an inactive product
     * that an admin explicitly reviewed and turned down (has a
     * rejection_reason), as opposed to one a vendor simply disabled.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'inactive')->whereNotNull('rejection_reason');
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'inactive' && ! is_null($this->rejection_reason);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors
    public function getInquiriesCountAttribute()
    {
        return $this->vendorContacts()->count();
    }

    // Specifications flattened to printable strings, lists are joined and booleans become Yes/No
    public function getDisplaySpecificationsAttribute(): array
    {
        return collect($this->specifications ?? [])->map(fn($value) => match (true) {
            is_array($value) => implode(', ', $value),
            is_bool($value) => $value ? 'Yes' : 'No',
            default => (string) $value,
        })->all();
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function generateSlug()
    {

        $slug = Str::slug($this->title);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // In Product model, add accessor for image URLs
    public function getImageUrlsAttribute()
    {
        return collect($this->images)->map(function ($image) {
            return asset($image);
        })->toArray();
    }

    public function refreshRatingSummary(): void
    {
        $this->rating = round($this->reviews()->avg('rating') ?? 0, 1);
        $this->review_count = $this->reviews()->count();
        $this->saveQuietly();
    }

    // Categories listed in config/listing_forms.php (rent, sale) have their own post and edit form
    public function isRealEstate(): bool
    {
        return in_array($this->category?->slug, array_keys(config('listing_forms')), true);
    }

    // Only listings in a category that has its own listing form
    public function scopeRealEstate($query)
    {
        return $query->whereHas('category', fn ($category) => $category->whereIn('slug', array_keys(config('listing_forms'))));
    }

    // Video-only listings show a placeholder wherever a cover photo is expected, so images[0] never breaks
    public function getImagesAttribute($value): ?array
    {
        if ($value === null) {
            return null;
        }

        $images = json_decode($value, true) ?: [];

        return $images ?: ($this->videos ? [self::VIDEO_PLACEHOLDER] : []);
    }

    // The photos really uploaded for this listing, without the video placeholder
    public function storedImages(): array
    {
        return json_decode($this->attributes['images'] ?? '[]', true) ?: [];
    }

    // Owners can manage their own products, admins can manage any
    public function scopeManageableBy($query, User $user)
    {
        return $user->isAdmin() ? $query : $query->where('user_id', $user->id);
    }

    // Services show seller contact buttons instead of a price
    public function isService(): bool
    {
        return (bool) $this->category?->isService();
    }

    public function sellerContact(): SellerContact
    {
        return SellerContact::for($this->user);
    }

    // Chatable: the vendor who owns this listing receives buyer messages
    public function chatSeller(): User
    {
        return $this->user;
    }

    public function chatTitle(): string
    {
        return $this->title;
    }

    public function chatUrl(): string
    {
        return route('product.show', $this->slug);
    }

    public function chatThumbnail(): string
    {
        return $this->image_urls[0] ?? asset(self::VIDEO_PLACEHOLDER);
    }

    public function isBoosted(): bool
    {
        return $this->boost_expires_at !== null && $this->boost_expires_at->isFuture();
    }

    public function scopeBoosted($query)
    {
        return $query->whereNotNull('boost_expires_at')->where('boost_expires_at', '>', now());
    }



    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function scopeFromActiveSubscribers($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->whereHas('activeSubscription');
        });
    }
}
