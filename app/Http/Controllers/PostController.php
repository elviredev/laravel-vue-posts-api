<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * @desc Afficher la liste des posts du user authentifié
     * @route GET /dashboard/posts
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
      $user = auth()->user();
      $posts = auth()->user()->posts()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return PostResource::collection($posts)
          ->additional([
            'meta' => ['total_posts' => $user->posts()->count()],
          ]);
    }

    /**
     * @desc Enregistrer un post en BDD
     * @route POST /dashboard/posts
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);
        $data['slug'] = Str::slug($data['title']);


        // Gestion de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
          // Stockage dans `storage/app/public/images/posts`
          $imagePath = $request->file('image')->store('images/posts', 'public');
        }

        $data['image'] = $imagePath;

        // Créer le post pour le user authentifié
        auth()->user()->posts()->create($data);

        return response()->json([
           'status' => 'success',
           'message' => 'Post created successfully'
        ], 201);
    }

  /**
   * @desc Afficher un post spécifique
   * @route GET /dashboard/posts/{post}
   * @param $slug
   * @return PostResource
   */
    public function show($slug): PostResource
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return new PostResource($post);
    }

  /**
   * @desc Modifier un post
   * @route PUT /dashboard/posts/{post}
   * @param Request $request
   * @param $slug
   * @return PostResource
   */
  public function update(Request $request, $slug): PostResource
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    $data = $request->validate([
      'title' => ['required', 'string', 'max:255'],
      'body' => ['required', 'string'],
    ]);

    $post->update($data);
    return new PostResource($post);
  }


  /**
   * @desc Supprimer un post
   * @route DELETE /dashboard/posts/{post}
   * @param $slug
   * @return Application|Response|ResponseFactory
   */
    public function destroy($slug): Application|Response|ResponseFactory
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->delete();
        return response(null, 204);
    }

  /**
   * @desc Modifier une image dans un post
   * @route POST /dashboard/posts/{slug}/image
   * @param Request $request
   * @param $slug
   * @return JsonResponse
   */
  public function updateImage(Request $request, $slug): JsonResponse
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    $request->validate([
      'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ]);

    // Supprimer l'ancienne image si elle existe
    if ($post->image) {
      Storage::disk('public')->delete($post->image);
    }

    // Sauvegarde de la nouvelle image
    $imagePath = $request->file('image')->store('images/posts', 'public');
    $post->update(['image' => $imagePath]);

    return response()->json(['image' => $imagePath], 200);
  }

  /**
   * @desc Supprimer une image dans un post
   * @route DELETE /dashboard/posts/{slug}/image
   * @param $slug
   * @return JsonResponse
   */
  public function deleteImage($slug): JsonResponse
  {
    $post = Post::where('slug', $slug)->firstOrFail();

    if ($post->image) {
      Storage::disk('public')->delete($post->image);
      $post->update(['image' => null]);
    }

    return response()->json(['image' => null], 200);
  }
}
