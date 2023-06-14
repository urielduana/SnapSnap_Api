<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'post_id',
        'user_comment_id',
    ];

    public function postComment()
    {
        return $this->belongsTo(Post::class);
    }

    public function userComment()
    {
        return $this->belongsTo(User::class);
    }


}
