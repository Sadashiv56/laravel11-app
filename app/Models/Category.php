<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'parent_id', 'parent_child_id', 'status',
    ];

   public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    } 
}
