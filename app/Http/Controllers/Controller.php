<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator OpenApi",
 *      description="Test your Apis with a full documentation.",
 *
 *      @OA\Contact(
 *          email="rayanwork2014@gmail.com"
 *      ),
 *
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="apiKey",
 *      in="header",
 *      name="Authorization",
 *      description="Sanctum Bearer token authentication"
 *  )
 */
abstract class Controller
{
    //
}
