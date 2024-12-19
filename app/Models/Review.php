<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Define the table associated with the Review model (optional if it follows naming convention)
    protected $table = 'Review'; // Ensure this matches your actual table name

    // Define relationships if necessary
    public function user()
    {
        return $this->belongsTo(User::class, 'author'); // Assuming 'author' is the foreign key in the reviews table
    }

    
}
