<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\Author\AuthorDto;
use App\Helper\AuthorDtoHelper;

class AuthorDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private AuthorDtoHelper $helper;

    public function __construct(AuthorDtoHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        return $this->helper->fetchAll();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?AuthorDto
    {
        return $this->helper->fetchOne($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === AuthorDto::class;
    }
}
