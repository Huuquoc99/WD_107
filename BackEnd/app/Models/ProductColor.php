<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColor extends Model
{
    use HasFactory, SoftDeletes;

    

    protected $fillable = [
        "name",
        "color_code",
        "status",
    ];

    protected $casts = [
        "status" => "boolean",
    ];
}
