<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = false;
    use HasFactory;

    function para(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
       return $this->hasMany(Para::class,'article_id','id');
    }
}
