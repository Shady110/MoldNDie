<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mold extends Model
{
    use HasFactory;

    protected $primaryKey = 'mold_id';

    protected $fillable = [
        'title',
        'thumbnail',
        'description',
        'category_id',
        'application',
        'file_path',
        'downloads',
        'likes',
        'comments_count',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function media()
    {
        return $this->hasMany(MoldMedia::class, 'mold_id');
    }

    public function downloads()
    {
        return $this->hasMany(MoldDownload::class, 'mold_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'mold_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'mold_tags', 'mold_id', 'tag_id');
    }
}
