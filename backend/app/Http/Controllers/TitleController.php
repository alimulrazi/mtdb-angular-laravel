<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Image;
use App\Jobs\IncrementModelViews;
use App\Models\Listable;
use App\Models\Review;
use App\Models\Season;
use App\Services\Titles\Retrieve\PaginateTitles;
use App\Services\Titles\Retrieve\ShowTitle;
use App\Services\Titles\Store\StoreTitleData;
use App\Models\Title;
use App\Models\Video;
use Api\Core\BaseController;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TitleController extends BaseController
{
    public function __construct(
        private readonly Request $request,
        private readonly Title $title
    ) {}



    public function index(): JsonResponse
    {
        $this->authorize('index', Title::class);

        try {
            $pagination = app(PaginateTitles::class)->execute(
                $this->request->all(),
            );

            return $this->success(['pagination' => $pagination]);
        } catch (\Exception $e) {
            return $this->error('Failed to retrieve titles', [], 500);
        }
    }

    /**
     * @param string|integer $titleId
     * @param string $titleName
     * @param string $seasonNumber
     * @param string $episodeNumber
     * @return JsonResponse
     */
    public function show(
        $titleId,
        $titleName = null,
        $seasonNumber = null,
        $episodeNumber = null
    ) {
        $this->authorize('show', Title::class);

        // season ID can be based in either via query or url params
        $params = $this->request->all();
        if (!isset($params['seasonNumber'])) {
            $params['seasonNumber'] = $seasonNumber;
        }
        if (!isset($params['episodeNumber'])) {
            $params['episodeNumber'] = $episodeNumber;
        }

        $response = app(ShowTitle::class)->execute($titleId, $params);

        $this->dispatch(
            new IncrementModelViews(
                Title::MODEL_TYPE,
                $response['title']['id'],
            ),
        );

        $type = 'title';
        $dataForSeo = null;
        if ($episodeNumber = Arr::get($params, 'episodeNumber')) {
            $type = 'episode';
            // need to specify data for seo generator manually as episode will be
            // nested under title and placeholder replacement will not work otherwise
            $episode = Arr::first(
                $response['title']['season']['episodes'],
                function ($episode) use ($episodeNumber) {
                    return $episode['episode_number'] === (int) $episodeNumber;
                },
            );
            $dataForSeo = ['episode' => $episode];
        } elseif (Arr::get($params, 'seasonNumber')) {
            $type = 'season';
        }

        $options = [
            'prerender' => [
                'view' => "$type.show",
                'config' => "$type.show",
                'dataForSeo' => $dataForSeo,
            ],
        ];

        return $this->success($response, 200, $options);
    }

    public function showWithoutNameParam(
        $titleId,
        $seasonNumber = null,
        $episodeNumber = null
    ) {
        return $this->show($titleId, null, $seasonNumber, $episodeNumber);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $this->authorize('update', Title::class);

        $data = $this->request->all();
        $title = $this->title->findOrFail($id);

        $title = app(StoreTitleData::class)->execute($title, $data, [
            'overrideWithEmptyValues' => true,
        ]);

        return $this->success(['title' => $title]);
    }

    /**
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $this->authorize('store', Title::class);

        $title = $this->title->create($this->request->all());

        return $this->success(['title' => $title], 201);
    }

    public function destroy()
    {
        $this->authorize('destroy', Title::class);

        $titleIds = $this->request->get('ids');

        // seasons
        app(Season::class)
            ->whereIn('title_id', $titleIds)
            ->delete();

        // episodes
        $episodeIds = app(Episode::class)
            ->whereIn('title_id', $titleIds)
            ->pluck('id');
        app(Episode::class)
            ->whereIn('id', $episodeIds)
            ->delete();

        // images
        app(Image::class)
            ->whereIn('model_id', $titleIds)
            ->where('model_type', Title::class)
            ->delete();

        // list items
        app(Listable::class)
            ->whereIn('listable_id', $titleIds)
            ->where('listable_type', Title::class)
            ->delete();

        // reviews
        app(Review::class)
            ->whereIn('reviewable_id', $titleIds)
            ->where('reviewable_id', Title::class)
            ->delete();

        app(Review::class)
            ->whereIn('reviewable_id', $episodeIds)
            ->where('reviewable_id', Episode::class)
            ->delete();

        // tags
        DB::table('taggables')
            ->whereIn('taggable_id', $titleIds)
            ->where('taggable_type', Title::class)
            ->delete();

        // videos
        $videoIds = app(Video::class)
            ->whereIn('title_id', $titleIds)
            ->pluck('id');
        app(Video::class)
            ->whereIn('id', $videoIds)
            ->delete();

        DB::table('video_ratings')
            ->whereIn('video_id', $videoIds)
            ->delete();
        DB::table('video_captions')
            ->whereIn('video_id', $videoIds)
            ->delete();
        DB::table('video_reports')
            ->whereIn('video_id', $videoIds)
            ->delete();

        // titles
        $this->title->whereIn('id', $titleIds)->delete();

        return $this->success();
    }
}
