<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "catalogue_id",
        "name",
        "slug",
        "sku",
        "img_thumbnail",
        "price_regular",
        "price_sale",
        "short_description",
        "description",
        "screen_size",
        "battery_capacity",
        "camera_resolution",
        "operating_system",
        "processor",
        "ram",
        ",storage",
        "sim_type",
        "network_connectivity",
        "is_active",
        "is_hot_deal",
        "is_good_deal",
        "is_new",
        "is_show_home",
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hot_deal' => 'boolean',
        'is_good_deal' => 'boolean',
        'is_new' => 'boolean',
        'is_show_home' => 'boolean',
    ];

    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
