<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPlatform extends Model
{
    protected $table = 'ProductPlatform';

    public $incrementing = false;
    public $timestamps  = false;
    protected $primaryKey = ['product', 'platform_name'];

    protected $fillable = [
        'product',
        'platform_name',
    ];

    public function _product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product', 'id');
    }
}
