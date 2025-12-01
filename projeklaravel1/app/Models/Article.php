<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'author',
        'views',
        'is_trending',
        'is_featured',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_trending' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->latest();
    }

    public function getTimeAgoAttribute()
    {
        $diff = $this->published_at->diffInMinutes(now());
        
        if ($diff < 60) {
            return $diff . ' menit lalu';
        } elseif ($diff < 1440) {
            return floor($diff / 60) . ' jam lalu';
        } else {
            return floor($diff / 1440) . ' hari lalu';
        }
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
