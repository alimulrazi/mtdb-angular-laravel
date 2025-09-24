<?php namespace Api\Core\Controllers;

use Api\Core\BaseController;
use Api\Core\Bootstrap\BootstrapData;
use Api\Core\Bootstrap\MobileBootstrapData;
use Illuminate\Http\JsonResponse;

class BootstrapController extends BaseController
{
    /**
     * Get data needed to bootstrap the application.
     *
     * @param BootstrapData $bootstrapData
     * @return JsonResponse
     */
    public function getBootstrapData(BootstrapData $bootstrapData)
    {
        return response()->json(['data' => $bootstrapData->init()->getEncoded()]);
    }

    public function getMobileBootstrapData(MobileBootstrapData  $bootstrapData)
    {
        return response()->json($bootstrapData->init()->get());
    }
}

