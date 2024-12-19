<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    // Specify the table if it does not follow the default naming convention
    protected $table = 'PaymentMethod';

    // Define fillable attributes for mass assignment
    protected $fillable = ['user_id', 'type', 'details'];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
