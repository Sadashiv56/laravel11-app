<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'books';


    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'date',
        'start_time',  
        'end_time',
        'user_id',
        'teacher_id',
        'product_id',
        'payment_id',
        'payment_status'
    ];
     public function products()
    {
        return $this->belongsToMany(Product::class, 'products');
    }

}
