<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'profile_id';

    protected $fillable = [
        'user_id',
        'bio',
        'profile_picture',
        'social_media_links',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
