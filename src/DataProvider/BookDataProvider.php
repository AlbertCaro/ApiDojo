<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\Book\BookDto;
use App\Helper\BookDtoHelper;

class BookDataProvider implements ContextAwareCollectionDataProviderInterface, ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private BookDtoHelper $helper;

    public function __construct(BookDtoHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        return $this->helper->fetchAll();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?BookDto
    {
        return $this->helper->fetchOne($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === BookDto::class;
    }
}
