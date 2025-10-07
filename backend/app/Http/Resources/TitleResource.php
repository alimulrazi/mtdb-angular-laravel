<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'original_title' => $this->original_title,
            'description' => $this->description,
            'poster' => $this->poster,
            'backdrop' => $this->backdrop,
            'release_date' => $this->release_date?->format('Y-m-d'),
            'year' => $this->year,
            'runtime' => $this->runtime,
            'rating' => $this->rating,
            'vote_count' => $this->vote_count,
            'popularity' => $this->popularity,
            'is_series' => $this->is_series,
            'season_count' => $this->when($this->is_series, $this->season_count),
            'episode_count' => $this->when($this->is_series, $this->episode_count),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}