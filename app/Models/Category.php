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
        'description',
        'parent_id',
        'svg',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Parent Category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Subcategories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Products in this category (many-to-many relationship)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products_pivots');
    }

    /**
     * Get all descendant categories.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Check if category has children.
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Get the root parent category.
     */
    public function getRootParentAttribute()
    {
        $parent = $this;
        while ($parent->parent_id !== null) {
            $parent = $parent->parent;
        }
        return $parent;
    }
}
