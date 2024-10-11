<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldDownload extends Model
{
    use HasFactory;

    protected $primaryKey = 'download_id';

    protected $fillable = [
        'mold_id',
        'user_id',
        'download_date',
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
