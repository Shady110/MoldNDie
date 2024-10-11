<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BlogTag extends Pivot
{
    protected $table = 'blog_tag';

    // Timestamps
    public $timestamps = true;

    // Fillable fields (if you have more attributes in the pivot table)
    protected $fillable = [
        'post_id', 
        'tag_id',
    ];
}
