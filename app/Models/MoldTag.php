<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoldTag extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'mold_id',
        'tag_id',
    ];
}
