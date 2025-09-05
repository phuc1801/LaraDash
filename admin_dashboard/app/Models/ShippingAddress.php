<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'name',
        'phone',
    ];

    // 1 địa chỉ giao hàng thuộc về 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 1 địa chỉ có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }
}
