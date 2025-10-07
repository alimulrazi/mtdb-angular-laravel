<?php

namespace Api;

use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *     title="MTDB API Documentation",
 *     version="1.0.0",
 *     description="API documentation for MTDB - Ultimate Movie & TV Database",
 *     @OA\Contact(
 *         email="admin@mtdb.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="MTDB API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Enter token in format (Bearer <token>)"
 * )
 */
class SwaggerController extends Controller
{
    //
}