<?php

namespace App\Helper;

use App\Dto\Book\BookDto;
use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookDtoHelper
{
    private EntityManagerInterface $entityManager;

    private BookRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, BookRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function fetchAll(): array
    {
        $books = $this->repository->findAll();

        return array_map(fn(Book $book) => new BookDto($book), $books);
    }

    public function fetchOne($id): ?BookDto
    {
        $book = $this->repository->find($id);

        if ($book) {
            return new BookDto($book);
        }

        return null;
    }

    public function persist(Book $book, BookDto $dto): BookDto
    {
        $book->setDataFromDto($dto);

        $author = $this->entityManager->find(Author::class, $dto->author->id);
        $book->setAuthor($author);

        if (!$this->entityManager->contains($book)) {
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();
        $this->entityManager->refresh($book);

        return new BookDto($book);
    }
}
