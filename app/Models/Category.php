<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'text_color',
        'bg_color',
    ];


    /**
     * posts
     *
     * @return void
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class);
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
        $query->where('title', 'like', "%{$value}%")
        ->orWhere('slug', 'like', "%{$value}%");
    }
}
