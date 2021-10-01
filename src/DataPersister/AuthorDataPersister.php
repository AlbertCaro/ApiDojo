<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\Author\AuthorDto;
use App\Entity\Author;
use App\Helper\AuthorDtoHelper;
use Doctrine\ORM\EntityManagerInterface;

class AuthorDataPersister implements ContextAwareDataPersisterInterface
{
    private AuthorDtoHelper $helper;

    private EntityManagerInterface $entityManager;

    public function __construct(AuthorDtoHelper $helper, EntityManagerInterface $entityManager)
    {
        $this->helper = $helper;
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof AuthorDto;
    }

    public function persist($data, array $context = []): AuthorDto
    {
        $operation = $context['item_operation_name'] ?? $context['collection_operation_name'];

        $author = match ($operation) {
            'put' => $this->entityManager->find(Author::class, $data->id),
            'post' => new Author(),
            default => throw new \RuntimeException("Invalid HTTP method"),
        };

        return $this->helper->persist($author, $data);
    }

    public function remove($data, array $context = [])
    {
        $author = $this->entityManager->find(Author::class, $data->id);

        if ($author !== null) {
            $this->entityManager->remove($author);

            $this->entityManager->flush();
        }
    }
}

