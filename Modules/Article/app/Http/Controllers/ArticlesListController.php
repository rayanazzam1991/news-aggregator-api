<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Http\Requests\GetArticlesListRequest;
use Modules\Article\Http\Resources\ArticlesListResource;
use Modules\Article\Http\Resources\PaginationResource;
use Modules\Article\Service\ArticleService;

class ArticlesListController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/articles/list",
     *     operationId="getArticlesList",
     *     tags={"Articles"},
     *     summary="Get a list of articles with optional filters",
     *     description="This endpoint returns a paginated list of articles based on optional filters like keywords, date, category, author, and source.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="keywords",
     *                 type="array",
     *                 description="Array of keywords to search for in articles.",
     *
     *                 @OA\Items(type="string")
     *             ),
     *
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date",
     *                 description="Filter articles by date in YYYY-MM-DD format.",
     *                 example="2024-10-18"
     *             ),
     *             @OA\Property(
     *                 property="category",
     *                 type="string",
     *                 description="Filter articles by category."
     *             ),
     *             @OA\Property(
     *                 property="author",
     *                 type="string",
     *                 description="Filter articles by author name."
     *             ),
     *             @OA\Property(
     *                 property="source",
     *                 type="string",
     *                 description="Filter articles by source."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="A list of articles with pagination metadata",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/ArticlesListResource")
     *             ),
     *
     *             @OA\Property(
     *                 property="pagination",
     *                 ref="#/components/schemas/PaginationResource"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Validation error"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(type="array", @OA\Items(type="string"))
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function __invoke(GetArticlesListRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     keywords:array<string> | null,
         *     date:string | null,
         *     category:string | null,
         *     author:string | null,
         *     srouce:string |null
         * } $requestedFilterData
         */
        $requestedFilterData = $request->validated();

        $filterParams = ArticleSearchFilter::fromRequest($requestedFilterData);

        $results = $this->articleService->getArticlesList($filterParams);
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(new Result(ArticlesListResource::collection($results), $pagination));
    }
}
