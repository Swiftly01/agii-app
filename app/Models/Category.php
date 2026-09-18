<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'icon',
        'specifications_template',
        'is_active',
        'parent_id',
    ];

    protected $casts = [
        'specifications_template' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    // Methods
    public function getSubcategoriesAttribute()
    {
        return $this->children()->active()->get();
    }

    public function getSpecificationFields()
    {
        return $this->specifications_template ?? [];
    }

    /**
     * IDs of every category nested under this one, at any depth.
     */
    public function allDescendantIds(): array
    {
        $ids = [];

        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->allDescendantIds());
        }

        return $ids;
    }

    /**
     * Product count for this category plus every category nested under it —
     * used to guard against deleting a category that would silently orphan
     * or cascade-delete products.
     */
    public function totalProductsCount(): int
    {
        $categoryIds = array_merge([$this->id], $this->allDescendantIds());

        return Product::whereIn('category_id', $categoryIds)->count();
    }
}
