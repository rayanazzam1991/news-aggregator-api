<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\PaginationResource;
use App\Helpers\ApiResponse\Result;
use App\Helpers\ApiResponse\SuccessResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Http\Requests\StoreCategoryRequest;
use Modules\Article\Http\Requests\UpdateCategoryRequest;
use Modules\Article\Http\Resources\CategoriesListResource;
use Modules\Article\Service\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     operationId="getCategoriesList",
     *     tags={"Categories"},
     *     summary="Get list of categories",
     *     description="Retrieve a paginated list of all categories.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CategoriesListResource")),
     *             @OA\Property(property="pagination", ref="#/components/schemas/PaginationResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $results = $this->categoryService->getList();
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(new Result(CategoriesListResource::collection($results), $pagination));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     operationId="createCategory",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     description="Create a new category with a specified name.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the category",
     *                 example="Technology"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Category created successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     name:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->categoryService->create($dataFromRequest);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Category created successfully'));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     operationId="updateCategory",
     *     tags={"Categories"},
     *     summary="Update an existing category",
     *     description="Update the name or status of a category by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", nullable=true, description="Name of the category", example="New Category Name"),
     *             @OA\Property(property="status", type="integer", nullable=true, description="Status of the category", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Category updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        /**
         * @var array{
         *     name:string | null,
         *     status:int | null
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->categoryService->update($dataFromRequest, $id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Category updated successfully'));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     operationId="deleteCategory",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     description="Delete a category by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Category deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Category deleted successfully'));
    }
}
