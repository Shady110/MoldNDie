<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'thumbnail',
        'title',
        'introduction',
        'content',
        'category_id',
        'publish_date',
        'update_date',
        'status',
        'comments_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'post_id');
    }

    public function media()
    {
        return $this->hasMany(BlogMedia::class, 'post_id');
    }
     
   public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'post_id', 'tag_id');
    }
}
