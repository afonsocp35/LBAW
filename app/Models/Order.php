<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Order'; // Nome da tabela no banco de dados

    protected $fillable = [
        'ordered_on',
        'delivered_on',
        'shipping_address',
        'status',
        'buyer',
        'payment',
    ];

    /**
     * Relacionamento com os itens do pedido.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Relacionamento com o comprador (usuário).
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer');
    }
}