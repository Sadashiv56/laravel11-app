<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    use HasFactory;
    protected $table = 'education_histories';

    protected $fillable = [
        'teacher_id', 'title', 'start_year', 'end_year','short_description'
    ];
}
