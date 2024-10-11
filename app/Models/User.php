<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory ,HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name',
        'phone_number',
        'country',
    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'user_id');
    }

    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    public function downloads()
    {
        return $this->hasMany(MoldDownload::class, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'user_id');
    }
}
