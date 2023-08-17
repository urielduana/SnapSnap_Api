<?php

namespace App\Models;

use App\Models\Tags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'image_url',
        'description',
        'user_id',
        'tag_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tagPost()
    {
        return $this->belongsTo(Tags::class);
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(1080)
            ->height(1080);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posts');
    }

    public function likedByUser($userId)
    {
        return $this->likes()->where('user_like_id', $userId)->exists();
    }

    public function addLike($userId)
    {
        $this->likes()->create([
            'user_like_id' => $userId,
        ]);
    }

    public function removeLike($userId)
    {
        $this->likes()->where('user_like_id', $userId)->delete();
    }
}
