<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $table = 'ShoppingCart';

    public $timestamps = false;
    

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = ['user_id', 'product', 'quantity', 'added_on'];

    protected function setKeysForSaveQuery($query)
    {
        $query->where('user_id', $this->getAttribute('user_id'))
              ->where('product', $this->getAttribute('product'));

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function _product()
    {
        return $this->belongsTo(Product::class, 'product');
    }

    /**
     * Get the name of the product associated with this shopping cart entry.
     */
    public function getNameProduct()
    {
        return $this->product?->template?->name ?? 'Unknown';
    }

    public function deleteCartItem($userId, $productId)
    {
        return ShoppingCart::where('user_id', $userId)
            ->where('product', $productId)
            ->delete();
    }

}
