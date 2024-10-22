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
    )
    {

    }

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
     * Store a newly created resource in storage.
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
        return ApiResponseHelper::sendSuccessResponse(new SuccessResult("UserPreference created successfully"));
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
    }

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
