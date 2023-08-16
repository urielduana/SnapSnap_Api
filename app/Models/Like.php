<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_like_id',
    ];

    public function postLike()
    {
        return $this->belongsTo(Post::class);
    }

    public function userLike()
    {
        return $this->belongsTo(User::class);
    }
}
