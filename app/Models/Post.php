<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable=[
        'image_url',
        'description',
        'user_id',
        'tag_id'
    ];

    public function userPost()
    {
        return $this->belongsTo(User::class);
    }

    public function tagPost()
    {
        return $this->belongsTo(Tag::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
