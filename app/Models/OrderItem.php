<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'OrderItem';

    protected $fillable = [
        'order_id',
        'product',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function _product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
}
