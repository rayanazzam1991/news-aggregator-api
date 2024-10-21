<?php

namespace Modules\Article\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\Result;
use App\Helpers\ApiResponse\SuccessResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Article\Http\Requests\StoreSourceRequest;
use Modules\Article\Http\Requests\UpdateSourceRequest;
use Modules\Article\Http\Resources\PaginationResource;
use Modules\Article\Http\Resources\SourcesListResource;
use Modules\Article\Service\SourceService;

class SourceController extends Controller
{
    public function __construct(
        private readonly SourceService $sourceService
    ) {}

    /**
     * @OA\Get(
     *     path="/sources",
     *     operationId="getSourcesList",
     *     tags={"Sources"},
     *     summary="Get list of sources",
     *     description="Retrieve a paginated list of all sources.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SourcesListResource")),
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
        $results = $this->sourceService->getList();
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(new Result(SourcesListResource::collection($results), $pagination));
    }

    /**
     * @OA\Post(
     *     path="/sources",
     *     operationId="createSource",
     *     tags={"Sources"},
     *     summary="Create a new source",
     *     description="Create a new source with a specified name.",
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
     *                 description="Name of the source",
     *                 example="Technology"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Source created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Source created successfully")
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
    public function store(StoreSourceRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     name:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->sourceService->create($dataFromRequest);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Source created successfully'));
    }

    /**
     * @OA\Put(
     *     path="/sources/{id}",
     *     operationId="updateSource",
     *     tags={"Sources"},
     *     summary="Update an existing source",
     *     description="Update the name or status of a source by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Source ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", nullable=true, description="Name of the source", example="New Source Name"),
     *             @OA\Property(property="status", type="integer", nullable=true, description="Status of the source", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Source updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Source updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Source not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(UpdateSourceRequest $request, int $id): JsonResponse
    {
        /**
         * @var array{
         *     name:string | null,
         *     status:int | null
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->sourceService->update($dataFromRequest, $id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Source updated successfully'));
    }

    /**
     * @OA\Delete(
     *     path="/sources/{id}",
     *     operationId="deleteSource",
     *     tags={"Sources"},
     *     summary="Delete a source",
     *     description="Delete a source by its ID.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Source ID",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Source deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Source deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Source not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->sourceService->delete($id);

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult(message: 'Source deleted successfully'));
    }
}
