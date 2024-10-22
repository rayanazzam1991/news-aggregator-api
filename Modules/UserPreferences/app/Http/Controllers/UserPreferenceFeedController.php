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
