<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{
    use HasFactory;

    protected $table = 'order_meta';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'book_id',
        'user_id',
        'teacher_id', 
    ];
}
