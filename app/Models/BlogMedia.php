<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogMedia extends Model
{
    use HasFactory;

    // Define the primary key
    protected $primaryKey = 'media_id';

    // Guard the primary key from mass assignment
    protected $guarded = ['media_id'];

    // Constants for media types (image, video)
    const MEDIA_TYPE_IMAGE = 'image';
    const MEDIA_TYPE_VIDEO = 'video';

    // Allow these fields to be mass assignable
    protected $fillable = [
        'post_id',
        'media_type',
        'media_path', // Updated from 'media_path' to 'file_path' for consistency
    ];

    // Define relationship to the BlogPost model
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    // Optional: Cast the media_type field if needed (for example, enum types)
    protected $casts = [
        'media_type' => 'string',
    ];
}
