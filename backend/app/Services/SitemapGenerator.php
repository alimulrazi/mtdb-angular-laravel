<?php namespace App\Services;

use App;
use App\Models\Episode;
use App\Models\ListModel;
use App\Models\NewsArticle;
use App\Models\Person;
use App\Models\Season;
use App\Models\Title;
use Api\Admin\Sitemap\BaseSitemapGenerator;

class SitemapGenerator extends BaseSitemapGenerator
{
    protected function getAppQueries(): array
    {
        return [
            app(Title::class)
                ->where('fully_synced', true)
                ->orWhereNull('tmdb_id')
                ->select(['id', 'name']),
            app(Person::class)
                ->where('fully_synced', true)
                ->orWhereNull('tmdb_id')
                ->select(['id', 'name']),
            app(Episode::class)->select([
                'id',
                'name',
                'title_id',
                'season_number',
                'episode_number',
            ]),
            app(Season::class)->select(['id', 'title_id', 'number']),
            app(ListModel::class)
                ->where('public', true)
                ->where('system', false)
                ->select(['id', 'name']),
            app(NewsArticle::class)->select(['id', 'title', 'slug']),
        ];
    }

    protected function getAppStaticUrls(): array
    {
        return ['browse?type=series', 'browse?type=movie', 'people', 'news'];
    }

    protected function addTitleLine(
        string $url,
        string $updatedAt,
        string $name
    ) {
        $this->addNewLine($url, $updatedAt, $name);
        $this->addNewLine("$url/full-credits", $updatedAt, $name);
    }
}
