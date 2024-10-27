<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\PaginationResource;
use App\Helpers\ApiResponse\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Filter\ArticleSearchFilter;
use Modules\Article\Http\Requests\GetArticlesListRequest;
use Modules\Article\Http\Resources\ArticleDetailsResource;
use Modules\Article\Http\Resources\ArticlesListResource;
use Modules\Article\Service\ArticleService;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $articleService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/articles/show/{id}",
     *     operationId="getArticleDetails",
     *     tags={"Articles"},
     *     summary="Get article details by ID",
     *     description="This endpoint returns the details of a specific article based on its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the article to retrieve",
     *
     *         @OA\Schema(
     *             type="integer",
     *             example=123
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Article details retrieved successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/ArticleDetailsResource"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Article not found"
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
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthenticated."
     *             )
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $result = $this->articleService->getArticleDetails($id);

        return ApiResponseHelper::sendResponse(new Result(ArticleDetailsResource::make($result)));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/articles/list",
     *     operationId="getArticlesList",
     *     tags={"Articles"},
     *     summary="Get a list of articles with optional filters",
     *     description="This endpoint returns a paginated list of articles based on optional filters such as keywords, date, category, author, and source.",
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
     *                 property="category_id",
     *                 type="integer",
     *                 description="Filter articles by category ID."
     *             ),
     *             @OA\Property(
     *                 property="author_id",
     *                 type="integer",
     *                 description="Filter articles by author ID."
     *             ),
     *             @OA\Property(
     *                 property="source_id",
     *                 type="integer",
     *                 description="Filter articles by source ID."
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
    public function list(GetArticlesListRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     title:string | null,
         *     keywords:array<string> | null,
         *     date:string | null,
         *     category_id:int | null,
         *     source_id:int |null,
         *     author_id:int | null
         * } $requestedFilterData
         */

        $requestedFilterData = $request->validated();

        $filterParams = ArticleSearchFilter::fromRequest($requestedFilterData);

        $results = $this->articleService->getArticlesList($filterParams);
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(new Result(ArticlesListResource::collection($results), $pagination));
    }
}
