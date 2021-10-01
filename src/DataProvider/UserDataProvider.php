<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\User\UserDto;
use App\Helper\UserDtoHelper;

class UserDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private UserDtoHelper $helper;

    public function __construct(UserDtoHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        return $this->helper->fetchAll();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?UserDto
    {
        return $this->helper->fetchOne($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === UserDto::class;
    }
}
