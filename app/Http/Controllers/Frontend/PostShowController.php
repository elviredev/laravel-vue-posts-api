<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostShowController extends Controller
{
    /**
     * @desc Retourne un single post
     * @route GET /posts/{post:slug}
     * @param Post $post
     * @return Post
     */
    public function __invoke(Post $post): Post
    {
        return $post;
    }
}
