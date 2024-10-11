<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
