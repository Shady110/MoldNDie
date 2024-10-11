<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'tags';

    // Primary Key
    protected $primaryKey = 'tag_id';

    // Timestamps
    public $timestamps = true;

    // Fillable fields
    protected $fillable = [
        'name'
    ];

    // Define the many-to-many relationship with BlogPost
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_tag', 'tag_id', 'post_id');
    }
}
