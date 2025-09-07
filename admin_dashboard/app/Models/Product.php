<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'real_price',
        'quantity',
        'category_id',
        'status',
        'outline',
        'unit',
        'slug',
        'guarantee',
        'rating_avg',
        'rating_count',
        
    ];
}
