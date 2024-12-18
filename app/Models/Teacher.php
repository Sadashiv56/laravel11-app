<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Teacher extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'teachers';

    protected $fillable = [
        'name', 'email', 'mobile', 'about_teacher','social_media','product_id','user_id','image'
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products');
    }
}
