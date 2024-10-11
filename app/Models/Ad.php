<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $primaryKey = 'ad_id';

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'link',
        'status',
    ];
}
