<?php

namespace Api\Admin\Sitemap;

use Api\Core\BaseController;
use Illuminate\Http\JsonResponse;

class SitemapController extends BaseController
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    /**
     * @return JsonResponse
     */
    public function generate()
    {
        $sitemap = class_exists('App\Services\SitemapGenerator') ? app('App\Services\SitemapGenerator') : app(BaseSitemapGenerator::class);
        app($sitemap->generate());
        return $this->success([]);
    }
}

