<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address_id',
    ];

    // 1 order thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 order thuộc về 1 địa chỉ giao hàng
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }
}
