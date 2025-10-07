# Swagger API Documentation Setup

This guide explains how to set up and use Swagger API documentation for the MTDB Laravel backend.

## Installation

### Method 1: Using the Installation Script
Run the provided batch script:
```bash
install-swagger.bat
```

### Method 2: Manual Installation

1. **Install the Swagger package:**
```bash
composer require darkaonline/l5-swagger --ignore-platform-reqs
```

2. **Publish the configuration:**
```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

3. **Generate the API documentation:**
```bash
php artisan l5-swagger:generate
```

4. **Clear cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Accessing the Documentation

Once installed, you can access the Swagger UI at:
```
http://localhost:8082/secure/documentation
```

## Configuration

The Swagger configuration is located at:
```
config/l5-swagger.php
```

Key configuration options:
- **API Title**: MTDB API Documentation
- **Base URL**: http://localhost:8082
- **Security**: Sanctum token authentication
- **Scan Paths**: app/Api directory

## Adding Documentation to Controllers

### Basic Controller Documentation

Add the following annotations to your controller class:

```php
/**
 * @OA\Tag(
 *     name="YourTag",
 *     description="Description of your API endpoints"
 * )
 */
class YourController extends BaseController
{
    // Controller methods
}
```

### Method Documentation Examples

#### GET Endpoint
```php
/**
 * @OA\Get(
 *     path="/secure/your-endpoint",
 *     tags={"YourTag"},
 *     summary="Brief description",
 *     description="Detailed description",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="param_name",
 *         in="query",
 *         description="Parameter description",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     )
 * )
 */
```

#### POST Endpoint with JSON Body
```php
/**
 * @OA\Post(
 *     path="/secure/your-endpoint",
 *     tags={"YourTag"},
 *     summary="Create resource",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"field1","field2"},
 *             @OA\Property(property="field1", type="string", example="value1"),
 *             @OA\Property(property="field2", type="integer", example=123)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Resource created successfully"
 *     )
 * )
 */
```

#### File Upload Endpoint
```php
/**
 * @OA\Post(
 *     path="/secure/uploads",
 *     tags={"Files"},
 *     summary="Upload file",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="file",
 *                     type="string",
 *                     format="binary"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="File uploaded successfully"
 *     )
 * )
 */
```

## Authentication

The API uses Sanctum token authentication. To test authenticated endpoints:

1. Login through the `/secure/auth/login` endpoint
2. Copy the token from the response
3. In Swagger UI, click the "Authorize" button
4. Enter: `Bearer YOUR_TOKEN_HERE`
5. Click "Authorize"

## Regenerating Documentation

After adding new annotations, regenerate the documentation:
```bash
php artisan l5-swagger:generate
```

## Environment Variables

Add these to your `.env` file for customization:
```env
L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=http://localhost:8082
L5_SWAGGER_UI_DARK_MODE=false
```

## Documented Controllers

The following controllers have been documented:
- **Authentication**: LoginController, RegisterController
- **Users**: UserController (CRUD operations)
- **Files**: FileEntriesController (upload, list, manage files)

## Next Steps

1. Run the installation script or manual commands
2. Access the documentation at the provided URL
3. Add annotations to your remaining controllers
4. Test your API endpoints through the Swagger UI

## Troubleshooting

- **404 Error**: Make sure you've run `php artisan route:clear`
- **Empty Documentation**: Run `php artisan l5-swagger:generate`
- **Permission Issues**: Check file permissions in storage directory
- **Cache Issues**: Run `php artisan config:clear && php artisan cache:clear`