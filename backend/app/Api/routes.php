<?php

use Api\Admin\Analytics\AnalyticsController;
use Api\Admin\Appearance\Controllers\AppearanceController;
use Api\Admin\Appearance\Controllers\IconController;
use Api\Admin\Appearance\Controllers\MenuCategoriesController;
use Api\Admin\Appearance\Themes\CssThemeController;
use Api\Admin\CacheController;
use Api\Admin\Sitemap\SitemapController;
use Api\Auth\Controllers\AccessTokenController;
use Api\Auth\Controllers\ChangePasswordController;
use Api\Auth\Controllers\LoginController;
use Api\Auth\Controllers\RegisterController;
use Api\Auth\Controllers\ResetPasswordController;
use Api\Auth\Controllers\SendPasswordResetEmailController;
use Api\Auth\Controllers\SocialAuthController;
use Api\Auth\Controllers\UserAvatarController;
use Api\Auth\Controllers\UserController;
use Api\Auth\Controllers\UserPermissionsController;
use Api\Auth\Controllers\VerifyEmailController;
use Api\Auth\Roles\RolesController;
use Api\Auth\Roles\UserRolesController;
use Api\Billing\Gateways\Paypal\PaypalController;
use Api\Billing\Gateways\Paypal\PaypalWebhookController;
use Api\Billing\Gateways\Stripe\StripeController;
use Api\Billing\Gateways\Stripe\StripeWebhookController;
use Api\Billing\Invoices\InvoiceController;
use Api\Billing\Plans\BillingPlansController;
use Api\Billing\Subscriptions\SubscriptionsController;
use Api\Comments\Controllers\CommentableController;
use Api\Comments\Controllers\CommentController;
use Api\Core\Controllers\BootstrapController;
use Api\Core\Controllers\HomeController;
use Api\Core\Controllers\UpdateController;
use Api\Core\Values\ValueListsController;
use Api\Csv\BaseCsvExportController;
use Api\Csv\CommonCsvExportController;
use Api\Domains\CustomDomainController;
use Api\Files\Chunks\ChunkedUploadsController;
use Api\Files\Controllers\AddPreviewTokenController;
use Api\Files\Controllers\DownloadFileController;
use Api\Files\Controllers\FileEntriesController;
use Api\Files\Controllers\PublicUploadsController;
use Api\Files\Controllers\ServerMaxUploadSizeController;
use Api\Files\Controllers\UploadFaviconController;
use Api\Localizations\LocalizationsController;
use Api\Notifications\NotificationController;
use Api\Notifications\NotificationSubscriptionsController;
use Api\Pages\ContactPageController;
use Api\Pages\CustomPageController;
use Api\Search\Controllers\ModelSearchController;
use Api\Search\Controllers\SearchSettingsController;
use Api\Settings\SettingsController;
use Api\Tags\TagController;
use Api\Validation\CheckPasswordController;
use Api\Validation\RecaptchaController;
use Api\Workspaces\Controllers\WorkspaceController;
use Api\Workspaces\Controllers\WorkspaceInvitesController;
use Api\Workspaces\Controllers\WorkspaceMembersController;
use Api\Workspaces\UserWorkspacesController;

Route::group(['prefix' => 'secure', 'middleware' => 'web'], function () {
    //BOOTSTRAP
    Route::get('bootstrap-data', [BootstrapController::class, 'getBootstrapData']);

    // LOGIN
    Route::post('auth/register', [RegisterController::class, 'register']);
    Route::post('auth/login', [LoginController::class, 'login']);
    Route::post('auth/logout', [LoginController::class, 'logout']);

    // FORGOT/RESET PASSWORD
    Route::post('auth/password/email', [SendPasswordResetEmailController::class, 'sendResetLinkEmail']);
    Route::post('auth/password/reset', [ResetPasswordController::class, 'reset']);

    // VERIFY EMAIL
    Route::post('auth/email/verify/resend', [VerifyEmailController::class, 'resend']);
    Route::get('auth/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify');

    //SOCIAL AUTHENTICATION
    Route::get('auth/social/{provider}/connect', [SocialAuthController::class, 'connect']);
    Route::get('auth/social/{provider}/login', [SocialAuthController::class, 'login']);
    Route::get('auth/social/{provider}/retrieve-profile', [SocialAuthController::class, 'retrieveProfile']);
    Route::get('auth/social/{provider}/callback', [SocialAuthController::class, 'loginCallback']);
    Route::post('auth/social/extra-credentials', [SocialAuthController::class, 'extraCredentials']);
    Route::post('auth/social/{provider}/disconnect', [SocialAuthController::class, 'disconnect']);

    //USERS
    Route::apiResource('users', UserController::class)->except(['destroy']);
    Route::delete('users/{ids}', [UserController::class, 'destroy']);
    Route::post('access-tokens', [AccessTokenController::class, 'store']);
    Route::delete('access-tokens/{tokenId}', [AccessTokenController::class, 'destroy']);
    Route::post('users/csv/export', [CommonCsvExportController::class, 'exportUsers']);

    //ROLES
    Route::get('roles', [RolesController::class, 'index']);
    Route::post('roles', [RolesController::class, 'store']);
    Route::put('roles/{id}', [RolesController::class, 'update']);
    Route::delete('roles/{id}', [RolesController::class, 'destroy']);
    Route::post('roles/{id}/add-users', [RolesController::class, 'addUsers']);
    Route::post('roles/{id}/remove-users', [RolesController::class, 'removeUsers']);

    //USER PASSWORD
    Route::post('users/{id}/password/change', [ChangePasswordController::class, 'change']);

    //USER AVATAR
    Route::post('users/{id}/avatar', [UserAvatarController::class, 'store']);
    Route::delete('users/{id}/avatar', [UserAvatarController::class, 'destroy']);

    //USER ROLES
    Route::post('users/{id}/roles/attach', [UserRolesController::class, 'attach']);
    Route::post('users/{id}/roles/detach', [UserRolesController::class, 'detach']);

    //USER PERMISSIONS
    Route::post('users/{id}/permissions/add', [UserPermissionsController::class, 'add']);
    Route::post('users/{id}/permissions/remove', [UserPermissionsController::class, 'remove']);

    // CHUNKED UPLOADS
    Route::post('uploads/sessions/load', [ChunkedUploadsController::class, 'load']);
    Route::post('uploads/sessions/chunks', [ChunkedUploadsController::class, 'storeChunk']);

    //UPLOADS
    Route::get('uploads/server-max-file-size', [ServerMaxUploadSizeController::class, 'index']);
    Route::get('uploads', [FileEntriesController::class, 'index']);
    Route::get('uploads/download', [DownloadFileController::class, 'download']);
    Route::post('uploads/images', [PublicUploadsController::class, 'images']);
    Route::delete('uploads/images', [FileEntriesController::class, 'destroy']);
    Route::post('uploads/videos', [PublicUploadsController::class, 'videos']);
    Route::post('uploads/favicon', [UploadFaviconController::class, 'store']);
    Route::get('uploads/{id}', [FileEntriesController::class, 'show']);
    Route::post('uploads', [FileEntriesController::class, 'store']);
    Route::put('uploads/{id}', [FileEntriesController::class, 'update']);
    Route::delete('uploads', [FileEntriesController::class, 'destroy']);
    Route::post('uploads/{id}/add-preview-token', [AddPreviewTokenController::class, 'store']);

    // PAGES
    Route::apiResource('page', CustomPageController::class);

    //VALUE LISTS
    Route::get('value-lists/{names}', [ValueListsController::class, 'index']);

    //SETTINGS
    Route::get('settings', [SettingsController::class, 'index']);
    Route::post('settings', [SettingsController::class, 'persist']);

    // APPEARANCE EDITOR
    Route::post('admin/appearance', [AppearanceController::class, 'save']);
    Route::get('admin/appearance/values', [AppearanceController::class, 'getValues']);
    Route::get('admin/icons', [IconController::class, 'index']);

    // MENUS
    Route::get('admin/appearance/menu-categories', [MenuCategoriesController::class, 'index']);

    // CSS THEME
    Route::apiResource('css-theme', CssThemeController::class);

    //LOCALIZATIONS
    Route::get('localizations', [LocalizationsController::class, 'index']);
    Route::post('localizations', [LocalizationsController::class, 'store']);
    Route::put('localizations/{id}', [LocalizationsController::class, 'update']);
    Route::delete('localizations/{id}', [LocalizationsController::class, 'destroy']);
    Route::get('localizations/{name}', [LocalizationsController::class, 'show']);

    //OTHER ADMIN ROUTES
    Route::get('admin/analytics/stats', [AnalyticsController::class, 'stats']);
    Route::post('cache/flush', [CacheController::class, 'flush']);

    // SEARCH
    Route::get('search/global/model', [ModelSearchController::class, 'index']);
    Route::get('admin/search/models', [SearchSettingsController::class, 'getSearchableModels']);
    Route::post('admin/search/import', [SearchSettingsController::class, 'import']);

    //billing plans
    Route::apiResource('billing-plan', BillingPlansController::class);
    Route::post('billing-plan/sync', [BillingPlansController::class, 'sync']);

    // SWAGGER DOCUMENTATION
    Route::get('api-docs.json', function () {
        return response()->json([
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'MTDB API Documentation',
                'version' => '1.0.0',
                'description' => 'Complete API documentation for MTDB - Ultimate Movie & TV Database'
            ],
            'servers' => [
                ['url' => 'http://localhost:8082', 'description' => 'Development Server']
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT'
                    ]
                ]
            ],
            'paths' => [
                '/secure/auth/login' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'User login',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email'],
                                            'password' => ['type' => 'string']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Login successful'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ]
                ],
                '/secure/auth/register' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'User registration',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password', 'password_confirmation'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email'],
                                            'password' => ['type' => 'string'],
                                            'password_confirmation' => ['type' => 'string']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Registration successful'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ]
                ],
                '/secure/auth/logout' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'User logout',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Logout successful']
                        ]
                    ]
                ],
                '/secure/users' => [
                    'get' => [
                        'tags' => ['Users'],
                        'summary' => 'Get users list',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer']],
                            ['name' => 'per_page', 'in' => 'query', 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Users list retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Users'],
                        'summary' => 'Create new user',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email'],
                                            'password' => ['type' => 'string'],
                                            'first_name' => ['type' => 'string'],
                                            'last_name' => ['type' => 'string']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => ['description' => 'User created successfully']
                        ]
                    ]
                ],
                '/secure/users/{id}' => [
                    'get' => [
                        'tags' => ['Users'],
                        'summary' => 'Get user details',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'User details retrieved successfully']
                        ]
                    ],
                    'put' => [
                        'tags' => ['Users'],
                        'summary' => 'Update user',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'User updated successfully']
                        ]
                    ]
                ],
                '/secure/uploads' => [
                    'get' => [
                        'tags' => ['Files'],
                        'summary' => 'Get file entries',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'userId', 'in' => 'query', 'schema' => ['type' => 'integer']],
                            ['name' => 'page', 'in' => 'query', 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'File entries retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Files'],
                        'summary' => 'Upload file',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'multipart/form-data' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'file' => ['type' => 'string', 'format' => 'binary'],
                                            'parentId' => ['type' => 'integer'],
                                            'disk' => ['type' => 'string']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => ['description' => 'File uploaded successfully']
                        ]
                    ]
                ],
                '/secure/settings' => [
                    'get' => [
                        'tags' => ['Settings'],
                        'summary' => 'Get application settings',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Settings retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Settings'],
                        'summary' => 'Update application settings',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Settings updated successfully']
                        ]
                    ]
                ],
                '/secure/roles' => [
                    'get' => [
                        'tags' => ['Roles'],
                        'summary' => 'Get roles list',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Roles retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Roles'],
                        'summary' => 'Create new role',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Role created successfully']
                        ]
                    ]
                ],
                '/secure/billing-plan' => [
                    'get' => [
                        'tags' => ['Billing'],
                        'summary' => 'Get billing plans',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Billing plans retrieved successfully']
                        ]
                    ]
                ],
                '/secure/billing/subscriptions' => [
                    'get' => [
                        'tags' => ['Billing'],
                        'summary' => 'Get user subscriptions',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Subscriptions retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Billing'],
                        'summary' => 'Create new subscription',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Subscription created successfully']
                        ]
                    ]
                ],
                '/secure/localizations' => [
                    'get' => [
                        'tags' => ['Localization'],
                        'summary' => 'Get localizations',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Localizations retrieved successfully']
                        ]
                    ]
                ],
                '/secure/tags' => [
                    'get' => [
                        'tags' => ['Tags'],
                        'summary' => 'Get tags list',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Tags retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Tags'],
                        'summary' => 'Create new tag',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Tag created successfully']
                        ]
                    ]
                ],
                '/secure/workspace' => [
                    'get' => [
                        'tags' => ['Workspace'],
                        'summary' => 'Get workspaces',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Workspaces retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Workspace'],
                        'summary' => 'Create new workspace',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Workspace created successfully']
                        ]
                    ]
                ],
                '/secure/comment' => [
                    'get' => [
                        'tags' => ['Comments'],
                        'summary' => 'Get comments',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Comments retrieved successfully']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Comments'],
                        'summary' => 'Create new comment',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '201' => ['description' => 'Comment created successfully']
                        ]
                    ]
                ],
                '/secure/notifications' => [
                    'get' => [
                        'tags' => ['Notifications'],
                        'summary' => 'Get user notifications',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Notifications retrieved successfully']
                        ]
                    ]
                ],
                '/secure/search/global/model' => [
                    'get' => [
                        'tags' => ['Search'],
                        'summary' => 'Global model search',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'query', 'in' => 'query', 'schema' => ['type' => 'string']]
                        ],
                        'responses' => [
                            '200' => ['description' => 'Search results retrieved successfully']
                        ]
                    ]
                ]
            ]
        ]);
    });
    
    Route::get('documentation', function () {
        return view('l5-swagger::index', [
            'documentation' => 'default',
            'urlToDocs' => url('secure/api-docs.json'),
            'useAbsolutePath' => config('l5-swagger.documentations.default.paths.use_absolute_path', true),
            'operationsSorter' => config('l5-swagger.defaults.operations_sort'),
            'configUrl' => config('l5-swagger.defaults.additional_config_url'),
            'validatorUrl' => config('l5-swagger.defaults.validator_url')
        ]);
    });

    // SUBSCRIPTIONS
    Route::get('billing/subscriptions', [SubscriptionsController::class, 'index']);
    Route::post('billing/subscriptions', [SubscriptionsController::class, 'store']);
    Route::post('billing/subscriptions/stripe', [StripeController::class, 'createSubscription']);
    Route::post('billing/subscriptions/stripe/finalize', [StripeController::class, 'finalizeSubscription']);
    Route::post('billing/subscriptions/paypal/agreement/create', [PaypalController::class, 'createSubscriptionAgreement']);
    Route::post('billing/subscriptions/paypal/agreement/execute', [PaypalController::class, 'executeSubscriptionAgreement']);
    Route::delete('billing/subscriptions/{id}', [SubscriptionsController::class, 'cancel']);
    Route::put('billing/subscriptions/{id}', [SubscriptionsController::class, 'update']);
    Route::post('billing/subscriptions/{id}/resume', [SubscriptionsController::class, 'resume']);
    Route::post('billing/subscriptions/{id}/change-plan', [SubscriptionsController::class, 'changePlan']);
    Route::post('billing/stripe/cards/add', [StripeController::class, 'addCard']);

    // NOTIFICATIONS
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::delete('notifications/{ids}', [NotificationController::class, 'destroy']);
    Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::get('notifications/{userId}/subscriptions', [NotificationSubscriptionsController::class, 'index']);
    Route::put('notifications/{userId}/subscriptions', [NotificationSubscriptionsController::class, 'update']);

    // TAGS
    Route::get('tags', [TagController::class, 'index']);
    Route::post('tags', [TagController::class, 'store']);
    Route::put('tags/{id}', [TagController::class, 'update']);
    Route::delete('tags/{tagIds}', [TagController::class, 'destroy']);

    // INVOICES
    Route::get('billing/invoice', [InvoiceController::class, 'index']);
    Route::get('billing/invoice/{uuid}', [InvoiceController::class, 'show']);

    // WORKSPACE
    Route::apiResource('workspace', WorkspaceController::class);
    Route::get('me/workspaces', [UserWorkspacesController::class, 'index']);
    Route::get('workspace/join/{workspaceInvite}', [WorkspaceMembersController::class, 'join']);
    Route::delete('workspace/{workspace}/member/{userId}', [WorkspaceMembersController::class, 'destroy']);
    Route::post('workspace/{workspace}/invite', [WorkspaceInvitesController::class, 'store']);
    Route::post('workspace/{workspace}/{workspaceInvite}/resend', [WorkspaceInvitesController::class, 'resend']);
    Route::post('workspace/{workspace}/member/{memberId}/change-role', [WorkspaceMembersController::class, 'changeRole']);
    Route::post('workspace/{workspace}/invite/{inviteId}/change-role', [WorkspaceInvitesController::class, 'changeRole']);
    Route::delete('workspace/invite/{workspaceInvite}', [WorkspaceInvitesController::class, 'destroy']);

    // COMMENTS
    Route::apiResource('comment', CommentController::class);
    Route::post('comment/restore', [CommentController::class, 'restore']);
    Route::get('commentable/comments', [CommentableController::class, 'loadComments']);

    // contact us page
    Route::post('contact-page', [ContactPageController::class, 'sendMessage']);
    Route::post('recaptcha/verify', [RecaptchaController::class, 'verify']);

    // SITEMAP
    Route::post('sitemap/generate', [SitemapController::class, 'generate']);

    // CSV
    Route::get('csv/download/{csvExport}', [BaseCsvExportController::class, 'download']);
});

// no need for "secure" prefix here, but need "web" middleware
Route::group(['middleware' => 'web'], function() {
    Route::get('update', [UpdateController::class, 'show']);
    Route::get('secure/update', [UpdateController::class, 'show']);
    Route::post('secure/update/run', [UpdateController::class, 'update']);

    // CUSTOM DOMAIN
    Route::group(['prefix' => 'secure', 'middleware' => 'customDomainsEnabled'], function() {
        Route::apiResource('custom-domain', CustomDomainController::class);
        Route::post('custom-domain/authorize/{method}', [CustomDomainController::class, 'authorizeCrupdate'])->where('method', 'store|update');
    });

    // FRONT-END ROUTES THAT NEED TO BE PRE-RENDERED
    Route::get('pages/{page}/{slug}', [CustomPageController::class, 'show'])->middleware(['web', 'prerenderIfCrawler']);

    // Laravel Auth routes with names so route('login') and similar calls don't error out
    Route::get('login', [HomeController::class, 'show'])->name('login');
    Route::get('register', [HomeController::class, 'show'])->name('register');
});

// NO "WEB" MIDDLEWARE IS APPLIED TO THESE ROUTES

Route::post('secure/password/check', [CheckPasswordController::class, 'check']);

// CUSTOM DOMAIN
Route::group(['prefix' => 'secure', 'middleware' => 'customDomainsEnabled'], function() {
    Route::post('custom-domain/validate/2BrM45vvfS/api', [CustomDomainController::class, 'validateDomainApi']);
    Route::get('custom-domain/validate/2BrM45vvfS', [CustomDomainController::class, 'validateDomain']);
});

// PAYPAL
Route::get('billing/paypal/callback/approved', [PaypalController::class, 'approvedCallback']);
Route::get('billing/paypal/callback/canceled', [PaypalController::class, 'canceledCallback']);
Route::get('billing/paypal/loading', [PaypalController::class, 'loadingPopup']);

// STRIPE
Route::post('billing/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('billing/paypal/webhook', [PaypalWebhookController::class, 'handleWebhook']);

