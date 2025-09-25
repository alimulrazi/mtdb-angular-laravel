<?php

use App\Http\Controllers\CaptionController;
use App\Http\Controllers\CaptionOrderController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\HomepageContentController;
use App\Http\Controllers\ImageOrderController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ImportMediaController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ListItemController;
use App\Http\Controllers\ListOrderController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonCreditsController;
use App\Http\Controllers\RelatedTitlesController;
use App\Http\Controllers\RelatedVideosController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\TitleCreditController;
use App\Http\Controllers\TitleTagsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VideoApproveController;
use App\Http\Controllers\VideoOrderController;
use App\Http\Controllers\VideoRatingController;
use App\Http\Controllers\VideoReportController;
use App\Http\Controllers\VideosController;
use Api\Core\Controllers\HomeController;

Route::group(['prefix' => 'secure'], function () {
    // titles
    Route::get('movies/{id}', [TitleController::class, 'show']);
    Route::get('series/{id}', [TitleController::class, 'show']);
    Route::get('titles/{id}', [TitleController::class, 'show']);
    Route::get('titles/{id}/related', [RelatedTitlesController::class, 'index']);
    Route::get('titles', [TitleController::class, 'index']);
    Route::post('titles', [TitleController::class, 'store']);
    Route::post('titles/credits', [TitleCreditController::class, 'store']);
    Route::post('titles/credits/reorder', [TitleCreditController::class, 'changeOrder']);
    Route::put('titles/credits/{id}', [TitleCreditController::class, 'update']);
    Route::delete('titles/credits/{id}', [TitleCreditController::class, 'destroy']);
    Route::put('titles/{id}', [TitleController::class, 'update']);
    Route::delete('titles', [TitleController::class, 'destroy']);

    // seasons
    Route::post('titles/{titleId}/seasons', [SeasonController::class, 'store']);
    Route::delete('seasons/{seasonId}', [SeasonController::class, 'destroy']);

    // episodes
    Route::get('episodes/{id}', [EpisodeController::class, 'show']);
    Route::post('seasons/{seasonId}/episodes', [EpisodeController::class, 'store']);
    Route::put('episodes/{id}', [EpisodeController::class, 'update']);
    Route::delete('episodes/{id}', [EpisodeController::class, 'destroy']);

    // people
    Route::get('people', [PersonController::class, 'index']);
    Route::get('people/{id}', [PersonController::class, 'show']);
    Route::get('people/{personId}/full-credits/{titleId}/{department}', [PersonCreditsController::class, 'fullTitleCredits']);
    Route::post('people', [PersonController::class, 'store']);
    Route::put('people/{id}', [PersonController::class, 'update']);
    Route::delete('people', [PersonController::class, 'destroy']);

    // search
    Route::get('search/{query}', [SearchController::class, 'index']);

    // lists
    Route::get('lists', [ListController::class, 'index']);
    Route::post('lists/auto-update-content', [ListController::class, 'autoUpdateContent']);
    Route::get('lists/{id}', [ListController::class, 'show']);
    Route::post('lists', [ListController::class, 'store']);
    Route::put('lists/{id}', [ListController::class, 'update']);
    Route::post('lists/{id}/reorder', [ListOrderController::class, 'changeOrder']);
    Route::delete('lists/{id}', [ListController::class, 'destroy']);
    Route::post('lists/{id}/add', [ListItemController::class, 'add']);
    Route::post('lists/{id}/remove', [ListItemController::class, 'remove']);

    // homepage
    Route::get('homepage/lists', [HomepageContentController::class, 'show']);

    // related videos
    Route::get('related-videos', [RelatedVideosController::class, 'index']);

    // images
    Route::post('images', [ImagesController::class, 'store']);
    Route::delete('images', [ImagesController::class, 'destroy']);
    Route::post('titles/{id}/images/change-order', [ImageOrderController::class, 'changeOrder']);

    // reviews
    Route::get('reviews', [ReviewController::class, 'index']);
    Route::post('reviews', [ReviewController::class, 'store']);
    Route::put('reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);

    // news
    Route::get('news', [NewsController::class, 'index']);
    Route::post('news/import-from-remote-provider', [NewsController::class, 'importFromRemoteProvider']);
    Route::get('news/{id}', [NewsController::class, 'show']);
    Route::post('news', [NewsController::class, 'store']);
    Route::put('news/{id}', [NewsController::class, 'update']);
    Route::delete('news', [NewsController::class, 'destroy']);

    // videos
    Route::get('videos', [VideosController::class, 'index']);
    Route::post('videos', [VideosController::class, 'store']);
    Route::put('videos/{id}', [VideosController::class, 'update']);
    Route::delete('videos/{ids}', [VideosController::class, 'destroy']);
    Route::post('videos/{id}/rate', [VideoRatingController::class, 'rate']);
    Route::post('videos/{video}/approve', [VideoApproveController::class, 'approve']);
    Route::post('videos/{video}/disapprove', [VideoApproveController::class, 'disapprove']);
    Route::post('videos/{video}/report', [VideoReportController::class, 'report']);
    Route::post('videos/{video}/log-play', [VideosController::class, 'logPlay']);
    Route::post('titles/{video}/videos/change-order', [VideoOrderController::class, 'changeOrder']);

    // title tags
    Route::post('titles/{titleId}/tags', [TitleTagsController::class, 'store']);
    Route::delete('titles/{titleId}/tags/{type}/{tagId}', [TitleTagsController::class, 'destroy']);

    // import
    Route::post('media/import', [ImportMediaController::class, 'importMediaItem']);
    Route::get('tmdb/import', [ImportMediaController::class, 'importViaBrowse']);

    // CAPTIONS
    Route::apiResource('caption', CaptionController::class);
    Route::post('caption/{videoId}/order', [CaptionOrderController::class, 'changeOrder']);

    // USER PROFILE
    Route::get('user-profile/{user}', [UserProfileController::class, 'show']);
    Route::get('user-profile/{user}/lists', [UserProfileController::class, 'loadLists']);
    Route::get('user-profile/{user}/ratings', [UserProfileController::class, 'loadRatings']);
    Route::get('user-profile/{user}/reviews', [UserProfileController::class, 'loadReviews']);
    Route::get('user-profile/{user}/comments', [UserProfileController::class, 'loadComments']);
});

// FRONT-END ROUTES THAT NEED TO BE PRE-RENDERED
Route::get('/', [HomepageContentController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('browse', [TitleController::class, 'index'])->middleware('prerenderIfCrawler');

// TITLE SHOW
Route::get('titles/{id}', [TitleController::class, 'showWithoutNameParam'])->middleware('prerenderIfCrawler');
Route::get('titles/{id}/{name}', [TitleController::class, 'show'])->middleware('prerenderIfCrawler');

// EPISODE SHOW
Route::get('titles/{id}/season/{season}/episode/{episode}', [TitleController::class, 'showWithoutNameParam'])->middleware('prerenderIfCrawler');
Route::get('titles/{id}/{name}/season/{season}/episode/{episode}', [TitleController::class, 'show'])->middleware('prerenderIfCrawler');

// SEASON SHOW
Route::get('titles/{id}/season/{season}', [TitleController::class, 'showWithoutNameParam'])->middleware('prerenderIfCrawler');
Route::get('titles/{id}/{name}/season/{season}', [TitleController::class, 'show'])->middleware('prerenderIfCrawler');

Route::get('people', [PersonController::class, 'index'])->middleware('prerenderIfCrawler');
Route::get('people/{id}', [PersonController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('people/{id}/{name}', [PersonController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('news', [NewsController::class, 'index'])->middleware('prerenderIfCrawler');
Route::get('news/{id}', [NewsController::class, 'show'])->middleware('prerenderIfCrawler');
Route::get('lists/{id}', [ListController::class, 'show'])->middleware('prerenderIfCrawler');

// CATCH ALL ROUTES AND REDIRECT TO HOME
Route::get('{all}', [HomeController::class, 'show'])->where('all', '.*');
