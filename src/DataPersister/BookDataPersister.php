<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\Book\BookDto;
use App\Entity\Book;
use App\Helper\BookDtoHelper;
use Doctrine\ORM\EntityManagerInterface;

class BookDataPersister implements ContextAwareDataPersisterInterface
{
    private BookDtoHelper $helper;

    private EntityManagerInterface $entityManager;

    public function __construct(BookDtoHelper $helper, EntityManagerInterface $entityManager)
    {
        $this->helper = $helper;
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof BookDto;
    }

    public function persist($data, array $context = []): BookDto
    {
        $operation = $context['item_operation_name'] ?? $context['collection_operation_name'];

        $book = match ($operation) {
            'put' => $this->entityManager->find(Book::class, $data->id),
            'post' => new Book(),
            default => throw new \RuntimeException("Invalid HTTP method"),
        };

        return $this->helper->persist($book, $data);
    }

    public function remove($data, array $context = [])
    {
        $book = $this->entityManager->find(Book::class, $data->id);

        if ($book !== null) {
            $this->entityManager->remove($book);

            $this->entityManager->flush();
        }
    }
}
