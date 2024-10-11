<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'mold_id',
        'user_id',
        'content',
        'date_posted',
        'status',
    ];

    public function mold()
    {
        return $this->belongsTo(Mold::class, 'mold_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
