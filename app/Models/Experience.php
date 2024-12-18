<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
     protected $table = 'experiences';

    protected $fillable = [
        'teacher_id', 'company_name', 'start_year', 'end_year','description'
    ];
}
