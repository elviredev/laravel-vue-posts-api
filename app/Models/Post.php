<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'is_published', 'body', 'image'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * @desc Relation : un post appartient à un user
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
