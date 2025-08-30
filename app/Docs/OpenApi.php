<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Flovide API",
 *     version="1.0.0",
 *     description="API documentation for Flovide payment system",
 *     @OA\Contact(
 *         email="support@flovide.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api/v1",
 *     description="Local development server"
 * )
 */
class OpenApi {}
