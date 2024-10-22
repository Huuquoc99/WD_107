<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalogue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "cover",
        "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function hide()
    {
        $this->is_active = 0;
        $this->save();
        
        foreach ($this->products as $product)
        {
            $product->hide();
        }
    }

    public function show()
    {
        $this->is_active = 1;
        $this->save();

        foreach ($this->products as $product)
        {
            $product->show();
        }
    }
}
