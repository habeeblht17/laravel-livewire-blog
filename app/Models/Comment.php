<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
    ];

    /**
     * users
     *
     * @return void
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * posts
     *
     * @return void
     */
    public function posts()
    {
        return $this->belongsTo(Post::class);
    }
}
