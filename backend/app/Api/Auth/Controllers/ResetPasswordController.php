<?php namespace Api\Auth\Controllers;

use Api\Core\BaseController;
use Api\Core\Bootstrap\BaseBootstrapData;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends BaseController
{
    use ResetsPasswords;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var BaseBootstrapData
     */
    private $bootstrapData;

    /**
     * @param Request $request
     * @param BaseBootstrapData $bootstrapData
     */
    public function __construct(Request $request, BaseBootstrapData $bootstrapData)
    {
        $this->middleware('guest');

        $this->request = $request;
        $this->bootstrapData = $bootstrapData;
    }

    /**
     * {@inheritdoc}
     */
    protected function sendResetResponse()
    {
        $user = $this->bootstrapData->getCurrentUser();
        return $this->success(['data' => $user]);
    }
}

