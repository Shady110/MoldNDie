<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldMedia extends Model
{
    use HasFactory;

    protected $primaryKey = 'media_id';

    protected $fillable = [
        'mold_id',
        'media_type',
        'media_path',
    ];

    public function mold()
    {
        return $this->belongsTo(Mold::class, 'mold_id');
    }
}
