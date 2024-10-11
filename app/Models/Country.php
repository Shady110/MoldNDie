<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';

    protected $fillable = [
        'name',
        'continent_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'country_code');
    }
}
