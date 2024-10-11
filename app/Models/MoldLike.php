<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoldLike extends Model
{
    protected $table = 'mold_likes';
    // Specify the primary key
    protected $primaryKey = 'like_id'; // Specify your custom primary key
    protected $fillable = ['user_id', 'mold_id'];

    // A like belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A like belongs to a mold
    public function mold()
    {
        return $this->belongsTo(Mold::class);
    }
}
