<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'locale',
        'title',
        'excerpt',
        'content',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
