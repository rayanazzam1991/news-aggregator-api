<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Http\Resources\ArticleDetailsResource;
use Modules\Article\Service\ArticleService;

class ArticleDetailsController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {}

    public function __invoke(int $id): JsonResponse
    {
        $result = $this->articleService->getArticleDetails($id);

        return ApiResponseHelper::sendResponse(new Result(ArticleDetailsResource::make($result)));
    }
}
