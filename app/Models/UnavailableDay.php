<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnavailableDay extends Model
{
    use HasFactory;

    protected $table = 'unavailable_day';

    protected $fillable = [
        'date',
        'type',
        'start_time',
        'end_time',
        'user_id',
        'teacher_id'
    ];

    protected $casts = [
        'type' => 'boolean',
    ];
}
