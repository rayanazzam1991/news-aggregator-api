<?php

namespace Modules\UserPreferences\Http\Controllers;

use App\Helpers\ApiResponse\ApiResponseHelper;
use App\Helpers\ApiResponse\PaginationResource;
use App\Helpers\ApiResponse\Result;
use App\Helpers\ApiResponse\SuccessResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\UserPreferences\DTO\StoreUserPreferenceDTO;
use Modules\UserPreferences\Http\Requests\StoreUserPreferenceRequest;
use Modules\UserPreferences\Http\Requests\UpdateUserPreferenceRequest;
use Modules\UserPreferences\Http\Requests\UserPreferenceListRequest;
use Modules\UserPreferences\Http\Resources\UserPreferencesListResource;
use Modules\UserPreferences\Service\UserPreferenceService;

class UserPreferencesController extends Controller
{
    public function __construct(
        private readonly UserPreferenceService $preferenceService
    ) {}

    /**
     * @OA\Post(
     *     path="/api/v1/userPreferences/list",
     *     operationId="listUserPreferences",
     *     tags={"User Preferences"},
     *     summary="Get list of user preferences",
     *     description="Returns a paginated list of user preferences for a given user.",
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"user_id"},
     *
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 description="ID of the user",
     *                 example=1
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="result", type="array", @OA\Items(ref="#/components/schemas/UserPreferencesListResource")),
     *             @OA\Property(property="paginate", ref="#/components/schemas/PaginationResource")
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
     *     )
     * )
     */
    public function list(UserPreferenceListRequest $request): JsonResponse
    {
        /**
         * @var array{
         *     user_id:int
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $results = $this->preferenceService->getList($dataFromRequest['user_id']);
        $pagination = PaginationResource::make($results);

        return ApiResponseHelper::sendResponse(
            new Result(UserPreferencesListResource::collection($results), $pagination)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/userPreferences",
     *     operationId="storeUserPreference",
     *     tags={"User Preferences"},
     *     summary="Store a user preference",
     *     description="Creates a new user preference with a specified type and value",
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="User preference details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"user_id", "preference_id", "preference_type"},
     *
     *             @OA\Property(property="user_id", type="integer", description="ID of the user", example=1),
     *             @OA\Property(property="preference_id", type="integer", description="ID of the preference", example=5),
     *             @OA\Property(property="preference_type", type="string", description="Type of the preference", example="category")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="UserPreference created successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="UserPreference created successfully"
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
     *     )
     * )
     */
    public function store(StoreUserPreferenceRequest $request): JsonResponse
    {

        /**
         * @var array{
         *     user_id:int,
         *     preference_id:int,
         *     preference_type:string
         * } $dataFromRequest
         */
        $dataFromRequest = $request->validated();
        $this->preferenceService->storeUserPreference(StoreUserPreferenceDTO::fromRequest($dataFromRequest));

        return ApiResponseHelper::sendSuccessResponse(new SuccessResult('UserPreference created successfully'));
    }

    /**
     * Show the specified resource.
     */
    public function show($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPreferenceRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
