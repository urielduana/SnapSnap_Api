<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_name',
        'color',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'tag_id');
    }

    public function userFavoriteTag()
    {
        return $this->hasMany(UserFavoriteTag::class);
    }
}
