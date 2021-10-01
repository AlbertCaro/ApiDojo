<?php

namespace App\Helper;

use App\Dto\Author\AuthorDto;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthorDtoHelper
{
    private EntityManagerInterface $entityManager;

    private AuthorRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function fetchAll(): array
    {
        $authors = $this->repository->findAll();

        return array_map(fn(Author $author) => new AuthorDto($author), $authors);
    }

    public function fetchOne($id): ?AuthorDto
    {
        $author = $this->repository->find($id);

        if ($author) {
            return new AuthorDto($author);
        }

        return null;
    }

    public function persist(Author $author, AuthorDto $dto): AuthorDto
    {
        $author->setDataFromDto($dto);

        if (!$this->entityManager->contains($author)) {
            $this->entityManager->persist($author);
        }

        $this->entityManager->flush();
        $this->entityManager->refresh($author);

        return new AuthorDto($author);
    }
}
