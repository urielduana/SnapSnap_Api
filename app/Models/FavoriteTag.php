<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag_id',
    ];

    public function userFavoriteTag()
    {
        return $this->belongsTo(User::class);
    }

    public function tagFavoriteTag()
    {
        return $this->belongsTo(Tag::class);
    }
}
