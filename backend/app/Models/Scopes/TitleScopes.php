<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait TitleScopes
{
    public function scopeMovies(Builder $query): Builder
    {
        return $query->where('is_series', false);
    }

    public function scopeSeries(Builder $query): Builder
    {
        return $query->where('is_series', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('popularity', 'desc');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('release_date', 'desc');
    }

    public function scopeHighRated(Builder $query, float $minRating = 7.0): Builder
    {
        return $query->where('tmdb_vote_average', '>=', $minRating);
    }

    public function scopeByGenre(Builder $query, string|array $genres): Builder
    {
        if (is_string($genres)) {
            $genres = [$genres];
        }

        return $query->whereHas('genres', function ($q) use ($genres) {
            $q->whereIn('name', $genres);
        });
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    public function scopeNeedsUpdate(Builder $query): Builder
    {
        return $query->where('allow_update', true)
                    ->where('fully_synced', false);
    }
}