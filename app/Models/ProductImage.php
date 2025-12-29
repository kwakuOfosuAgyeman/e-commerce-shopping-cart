<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_url',
        'image_type',
        'color',
        'alt_text',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to get primary image.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope to get front image.
     */
    public function scopeFront($query)
    {
        return $query->where('image_type', 'front');
    }

    /**
     * Scope to get back image.
     */
    public function scopeBack($query)
    {
        return $query->where('image_type', 'back');
    }

    /**
     * Scope to get side image.
     */
    public function scopeSide($query)
    {
        return $query->where('image_type', 'side');
    }

    /**
     * Scope to get images for a specific color.
     */
    public function scopeForColor($query, $color)
    {
        return $query->where('color', $color);
    }

    /**
     * Scope to get images without a color (default/general images).
     */
    public function scopeWithoutColor($query)
    {
        return $query->whereNull('color');
    }
}