<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      "id" => $this->id,
      "title" => $this->title,
      "slug" => $this->slug,
      "body" => $this->body,
      "user_id" => $this->user_id,
      "published" => $this->is_published,
      "createdAt" => $this->created_at->diffForHumans(),
      "image" => $this->image ? asset('storage/' . $this->image) : null, // Génère l'URL complète
    ];
  }
}
