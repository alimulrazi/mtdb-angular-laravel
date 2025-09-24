<?php namespace Api\Admin\Appearance\Controllers;

use File;
use Api\Core\BaseController;

class IconController extends BaseController
{
    public function index()
    {
        $this->authorize('index', 'AppearancePolicy');

        $iconFile = File::get(public_path('client/assets/icons/merged.svg'));

        preg_match_all('/id="([a-z09-]+?)"/m', $iconFile, $matches);

        return $this->success(['icons' => $matches[1]]);
    }
}

