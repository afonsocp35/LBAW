<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow the default Laravel naming convention
    protected $table = 'Payment';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the relationship with the User model.
     * Assuming the `user_id` column is the foreign key in the `Payment` table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define the relationship with the PaymentMethod model.
     * Assuming the `payment_method` column is the foreign key in the `Payment` table.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
}
