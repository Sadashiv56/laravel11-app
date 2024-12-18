<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendars';


    protected $fillable = [
        'user_id',
        'type',
        'day_of_week',  
        'start_time',
        'end_time',
        'date',
        'teacher_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
