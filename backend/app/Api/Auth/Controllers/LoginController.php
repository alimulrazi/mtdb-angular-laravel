<?php namespace Api\Auth\Controllers;

use App\Models\User;
use Auth;
use Api\Core\BaseController;
use Api\Core\Bootstrap\BootstrapData;
use Api\Settings\Settings;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */
class LoginController extends BaseController
{
    use AuthenticatesUsers;

    /**
     * @var BootstrapData
     */
    private $bootstrapData;

    /**
     * @var Settings
     */
    private $settings;

    /**
     * @param BootstrapData $bootstrapData
     * @param Settings $settings
     */
    public function __construct(BootstrapData $bootstrapData, Settings $settings)
    {
        $this->middleware('guest', ['except' => 'logout']);

        $this->bootstrapData = $bootstrapData;
        $this->settings = $settings;
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string|email_verified',
            'password' => 'required|string',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/secure/auth/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     description="Authenticate user and return bootstrap data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    protected function authenticated(Request $request, User $user)
    {
        if ($this->settings->get('single_device_login')) {
            Auth::logoutOtherDevices($request->get('password'));
        }

        $data = $this->bootstrapData->init()->getEncoded();

        return $this->success(['data' => $data]);
    }

    /**
     * @OA\Post(
     *     path="/secure/auth/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     description="Logout the authenticated user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout"
     *     )
     * )
     */
}

