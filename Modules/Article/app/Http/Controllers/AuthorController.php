<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\PaginationResource;
use App\Helpers\ApiResponse\Result;
use App\Helpers\ApiResponse\SuccessResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Http\Requests\StoreAuthorRequest;
use Modules\Article\Http\Requests\UpdateAuthorRequest;
use Modules\Article\Http\Resources\AuthorsListResource;
use Modules\Article\Service\AuthorService;

class AuthorController extends Controller
{
    public function __construct(
        private readonly AuthorService $authorService
    ) {}

    /**
     * @OA\Get(
     *     path="/authors",
     *     operationId="getAuthorsList",
     *     tags={"Authors"},
     *     summary="Get list of authors",
     *     description="Retrieve a paginated list of all authors.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AuthorsListResource")),
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
        $results = $this->authorService->getList();
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(new Result(AuthorsListResource::collection($results), $pagination));
    }

    /**
     * @OA\Post(
     *     path="/authors",
     *     operationId="createAuthor",
     *     tags={"Authors"},
     *     summary="Create a new author",
     *     description="Create a new author with a specified name.",
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
     *                 description="Name of the author",
     *                 example="Technology"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Author created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Author created successfully")
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
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        /**
         * @var array{
         *   name:string,
         *   status: int | null
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->authorService->create($dataFromRequest);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Author created successfully'));
    }

    /**
     * @OA\Put(
     *     path="/authors/{id}",
     *     operationId="updateAuthor",
     *     tags={"Authors"},
     *     summary="Update an existing author",
     *     description="Update the name or status of a author by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Author ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", nullable=true, description="Name of the author", example="New Author Name"),
     *             @OA\Property(property="status", type="integer", nullable=true, description="Status of the author", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Author updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Author updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Author not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(UpdateAuthorRequest $request, int $id): JsonResponse
    {
        /**
         * @var array{
         *     name:string | null,
         *     status:int | null
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->authorService->update($dataFromRequest, $id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Author updated successfully'));
    }

    /**
     * @OA\Delete(
     *     path="/authors/{id}",
     *     operationId="deleteAuthor",
     *     tags={"Authors"},
     *     summary="Delete a author",
     *     description="Delete a author by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Author ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Author deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Author deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Author not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->authorService->delete($id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Author deleted successfully'));
    }
}
