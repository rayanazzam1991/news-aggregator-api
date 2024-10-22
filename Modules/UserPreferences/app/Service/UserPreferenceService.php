<?php

namespace Modules\UserPreferences\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Article\Contracts\PreferencePublicInterface;
use Modules\Auth\Contracts\UserPublicInterface;
use Modules\UserPreferences\DTO\StoreUserPreferenceDTO;
use Modules\UserPreferences\Repository\UserPreferenceRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserPreferenceService
{

    public function __construct(
        private UserPublicInterface       $userPublicService,
        private PreferencePublicInterface $preferencePublicService,
        private UserPreferenceRepository  $preferenceRepository
    )
    {
    }

    public function storeUserPreference(StoreUserPreferenceDTO $preferenceDTO): void
    {
        // Validate the user ID via the Auth module
        if (!$this->validateUser($preferenceDTO->user_id)) {
            throw new NotFoundHttpException("User not found");
        }

        // Validate the preference via the UserPreference module
        if (!$this->validatePreference($preferenceDTO->preference_id, $preferenceDTO->preference_type)) {
            throw new NotFoundHttpException("Preference not found");
        }

        $dataToSave = array_filter([
            'user_id' => $preferenceDTO->user_id,
            'preference_id' => $preferenceDTO->preference_id,
            'preference_type' => $preferenceDTO->preference_type
        ], fn($item) => !is_null($item));

        $this->preferenceRepository->create($dataToSave);

    }

    public function getList(int $userId): LengthAwarePaginator
    {
        $rawData = $this->preferenceRepository->search($userId);
        foreach ($rawData as $data) {
            $data['preferenceType'] = $this->getPreferenceTypeName($data->preference_type);
            $data['preferenceValue'] = $this->getPreferenceDetails($data->preference_id, $data->preference_type);
        }
        return $rawData;
    }

    private function getUserDetails(int $userId): Model
    {
        return $this->userPublicService->getUser($userId);
    }

    private function getPreferenceDetails(int $preferenceId, string $preferenceType)
    {
        return $this->preferencePublicService->getPreference($preferenceId, $preferenceType);
    }


    private function getPreferenceTypeName(string $preferenceType)
    {
        return $this->preferencePublicService->getPreferenceTypeName($preferenceType);
    }

    private function validateUser(int $userId): bool
    {
        return $this->userPublicService->validateUser($userId);
    }

    private function validatePreference(int $preferenceId, string $preferenceType): bool
    {
        return $this->preferencePublicService->validatePreference($preferenceId, $preferenceType);
    }


}
