<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        // Who follows
        'user_id',
        // Who is followed
        'follower_id',
    ];

    public function userFollower()
    {
        return $this->belongsTo(User::class);
    }

    public function followerFollower()
    {
        return $this->belongsTo(User::class);
    }
}
