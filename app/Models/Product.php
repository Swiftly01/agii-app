<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

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
        'specifications',
        'rating',
        'review_count',
        'status',
        'featured',
        'negotiable',
        'tags',
        'meta_title',
        'meta_description',
        'views',
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'tags' => 'array',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'featured' => 'boolean',
        'negotiable' => 'boolean',
        'rating' => 'decimal:1',
        'views' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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
