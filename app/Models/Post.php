<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'image',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * author
     *
     * @return void
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * categories
     *
     * @return void
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * scopeSearch
     *
     * @param  mixed $query
     * @param  mixed $value
     * @return void
     */
    public function scopeSearch($query, $value)
    {
        $query->where('title', 'like', "%{$value}%")->orWhere('slug', 'like', "%{$value}%");
    }

    public function scopeWithCategory($query, $category)
    {
        $query->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });
    }

    /**
     * scopePublished
     *
     * @param  mixed $query
     * @return void
     */
    public function scopePublished($query)
    {
        $query->where('published_at', '<=', Carbon::now() );
    }

    /**
     * scopeFeatured
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeFeatured($query)
    {
        $query->where('featured', true);
    }

    /**
     * getExcerpt
     *
     * @return void
     */
    public function getExcerpt()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * getExcerpt2
     *
     * @return void
     */
    public function getExcerpt2()
    {
        return Str::limit(strip_tags($this->content), 10);
    }

    /**
     * getReadingTime
     *
     * @return void
     */
    public function getReadingTime()
    {
        $mins = round(str_word_count($this->content) / 150 );

        return ($mins < 1) ? 1 : $mins;
    }

    /**
     * getThumbnailImage
     *
     * @return void
     */
    public function getThumbnailUrl()
    {
        $isUrl = str_contains($this->image, 'https');

        return ($isUrl) ? $this->image : Storage::disk('public')->url($this->image);
    }
}
