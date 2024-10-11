<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
    ];

    public function molds()
    {
        return $this->hasMany(Mold::class, 'category_id');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'category_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
