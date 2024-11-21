<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_phone',
        'user_address',
        'user_note',
        'is_ship_user_same_user',
        'ship_user_name',
        'ship_user_email',
        'ship_user_phone',
        'ship_user_address',
        'ship_user_note',
        'status_order_id',
        'status_payment_id',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusOrder()
    {
        return $this->belongsTo(StatusOrder::class);
    }

    public function statusPayment()
    {
        return $this->belongsTo(StatusPayment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
