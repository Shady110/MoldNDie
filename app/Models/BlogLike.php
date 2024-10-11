<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogLike extends Model
{
    protected $table = 'blog_likes';

    // Specify the primary key
    protected $primaryKey = 'like_id'; // Specify your custom primary key

    protected $fillable = ['user_id', 'post_id'];

    // A like belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A like belongs to a post
    public function post()
    {
        return $this->belongsTo(BlogPost::class);
    }
}
