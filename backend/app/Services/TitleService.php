<?php

namespace App\Services;

use App\Models\Title;
use App\Models\Episode;
use App\Models\Season;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TitleService
{
    public function __construct(private readonly Title $title)
    {
    }

    public function findById(int $id): ?Title
    {
        return $this->title->with(['genres', 'seasons', 'videos'])->find($id);
    }

    public function create(array $data): Title
    {
        return $this->title->create($data);
    }

    public function update(Title $title, array $data): Title
    {
        $title->update($data);
        return $title->fresh();
    }

    public function delete(array $titleIds): bool
    {
        // Use database transactions for data integrity
        return \DB::transaction(function () use ($titleIds) {
            // Delete related records first
            $this->deleteRelatedRecords($titleIds);
            
            // Delete titles
            return $this->title->whereIn('id', $titleIds)->delete();
        });
    }

    private function deleteRelatedRecords(array $titleIds): void
    {
        // Get episode IDs for cleanup
        $episodeIds = Episode::whereIn('title_id', $titleIds)->pluck('id');

        // Delete seasons
        Season::whereIn('title_id', $titleIds)->delete();

        // Delete episodes
        Episode::whereIn('id', $episodeIds)->delete();

        // Delete other related records using proper relationships
        $this->title->whereIn('id', $titleIds)->each(function ($title) {
            $title->images()->delete();
            $title->videos()->delete();
            $title->genres()->detach();
            $title->keywords()->detach();
        });
    }

    public function getPopularTitles(int $limit = 20): Collection
    {
        return $this->title
            ->orderBy('popularity', 'desc')
            ->limit($limit)
            ->get();
    }

    public function searchTitles(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->title
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('original_title', 'LIKE', "%{$query}%")
            ->paginate($perPage);
    }
}