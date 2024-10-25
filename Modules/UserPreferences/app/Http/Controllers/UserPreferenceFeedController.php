<?php

namespace Modules\UserPreferences\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\PaginationResource;
use App\Helpers\ApiResponse\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\UserPreferences\Http\Requests\UserNewsFeedListRequest;
use Modules\UserPreferences\Http\Resources\UserPreferenceFeedListResource;
use Modules\UserPreferences\Service\UserPreferenceFeedService;

class UserPreferenceFeedController extends Controller
{
    public function __construct(
        private readonly UserPreferenceFeedService $feedService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/userPreferences/feed",
     *     operationId="getUserNewsFeed",
     *     tags={"User News Feed"},
     *     summary="Fetch user-specific news feed",
     *     description="Retrieve a paginated list of news articles based on the user's preferences.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         description="The ID of the user whose news feed is being fetched.",
     *
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User news feed successfully retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="result",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/UserPreferenceFeedListResource")
     *             ),
     *
     *             @OA\Property(
     *                 property="paginate",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="to", type="integer", example=10)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="An internal error occurred.")
     *         )
     *     )
     * )
     */
    public function __invoke(UserNewsFeedListRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     user_id:int
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $result = $this->feedService->fetch($dataFromRequest['user_id']);
        $pagination = PaginationResource::make($result);

        return ApiResponseHelper::sendResponse(new Result(
            result: UserPreferenceFeedListResource::collection($result),
            paginate: $pagination
        ));
    }
}
