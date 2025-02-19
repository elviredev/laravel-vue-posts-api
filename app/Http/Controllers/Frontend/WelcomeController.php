<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class WelcomeController extends Controller
{
    /**
     * @desc Récupérer tous les posts et les afficher
     * @route GET /
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(Post::all());
    }
}
