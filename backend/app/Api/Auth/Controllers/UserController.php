<?php namespace Api\Auth\Controllers;

use App\Models\User;
use Auth;
use Api\Auth\Actions\PaginateUsers;
use Api\Settings\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Api\Auth\UserRepository;
use Api\Core\BaseController;
use Api\Auth\Requests\ModifyUsers;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for user management"
 * )
 */
class UserController extends BaseController
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(
        User $user,
        UserRepository $userRepository,
        Request $request,
        Settings $settings
    ) {
        $this->user = $user;
        $this->request = $request;
        $this->userRepository = $userRepository;

        $this->middleware('auth', ['except' => ['show']]);
        $this->settings = $settings;
    }

    /**
     * @OA\Get(
     *     path="/secure/users",
     *     tags={"Users"},
     *     summary="Get users list",
     *     description="Get paginated list of users",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="pagination", type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $pagination = app(PaginateUsers::class)->execute($this->request->all());

        return $this->success(['pagination' => $pagination]);
    }

    /**
     * @OA\Get(
     *     path="/secure/users/{id}",
     *     tags={"Users"},
     *     summary="Get user details",
     *     description="Get details of a specific user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="with",
     *         in="query",
     *         description="Relations to include",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function show(User $user)
    {
        $relations = array_filter(
            explode(',', $this->request->get('with', '')),
        );
        $relations = array_merge(['roles', 'social_profiles'], $relations);

        if ($this->settings->get('envato.enable')) {
            $relations[] = 'purchase_codes';
        }

        if (Auth::id() === $user->id) {
            // TODO: remove after sanctum is added to all projects
            if (method_exists($user, 'tokens')) {
                $relations[] = 'tokens';
            }
        }

        $user->load($relations);

        $this->authorize('show', $user);

        return $this->success(['user' => $user]);
    }

    /**
     * @OA\Post(
     *     path="/secure/users",
     *     tags={"Users"},
     *     summary="Create new user",
     *     description="Create a new user account",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     )
     * )
     */
    public function store(ModifyUsers $request)
    {
        $this->authorize('store', User::class);

        $user = $this->userRepository->create($request->all());

        return $this->success(['user' => $user], 201);
    }

    /**
     * @param User $user
     * @param ModifyUsers $request
     *
     * @return JsonResponse
     */
    public function update(User $user, ModifyUsers $request)
    {
        $this->authorize('update', $user);

        $user = $this->userRepository->update($user, $request->all());

        return $this->success(['user' => $user]);
    }

    public function destroy(string $ids)
    {
        $userIds = explode(',', $ids);
        $shouldDeleteCurrentUser = $this->request->get('deleteCurrentUser');
        $this->authorize('destroy', [User::class, $userIds]);

        $users = $this->user->whereIn('id', $userIds)->get();

        // guard against current user or admin user deletion
        foreach ($users as $user) {
            if (!$shouldDeleteCurrentUser && $user->id === Auth::id()) {
                return $this->error(
                    "Could not delete currently logged in user: {$user->email}",
                );
            }

            if ($user->is_admin) {
                return $this->error(
                    "Could not delete admin user: {$user->email}",
                );
            }
        }

        $this->userRepository->deleteMultiple($users->pluck('id'));

        return $this->success();
    }
}

