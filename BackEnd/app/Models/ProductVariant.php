<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'product_capacity_id',
        'product_color_id',
        'quantity',
        'price',
        'status',
        'image'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function capacity()
    {
        return $this->belongsTo(ProductCapacity::class, 'product_capacity_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
