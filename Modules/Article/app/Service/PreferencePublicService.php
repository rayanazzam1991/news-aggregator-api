<?php

namespace Modules\Article\Service;

use Modules\Article\Contracts\PreferencePublicInterface;
use Modules\Article\Enums\PreferenceTypesEnum;
use Modules\Article\Repository\AuthorRepository;
use Modules\Article\Repository\CategoryRepository;
use Modules\Article\Repository\SourceRepository;

readonly class PreferencePublicService implements PreferencePublicInterface
{
    public function validatePreference(int $id, string $type): bool
    {
        switch ($type) {
            case PreferenceTypesEnum::AUTHOR->value:
                $authorRepository = new AuthorRepository;

                return $authorRepository->exists($id);
            case PreferenceTypesEnum::CATEGORY->value:
                $categoryRepository = new CategoryRepository;

                return $categoryRepository->exists($id);
            case PreferenceTypesEnum::SOURCE->value:
                $sourceRepository = new SourceRepository;

                return $sourceRepository->exists($id);
            default:
                return false;
        }
    }

    public function getPreference(int $id, string $type): string|null
    {
        switch ($type) {
            case PreferenceTypesEnum::AUTHOR->value:
                $authorRepository = new AuthorRepository;

                return $authorRepository->getById($id)?->name;
            case PreferenceTypesEnum::CATEGORY->value:
                $categoryRepository = new CategoryRepository;

                return $categoryRepository->getById($id)?->name;
            case PreferenceTypesEnum::SOURCE->value:
                $sourceRepository = new SourceRepository;

                return $sourceRepository->getById($id)?->name;
            default:
                return null;
        }
    }

    public function getPreferenceTypeName(string $type): string
    {
        return PreferenceTypesEnum::from($type)->name;
    }
}
