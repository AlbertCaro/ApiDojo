<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Test\BaseApiTestCase;
use Doctrine\Persistence\ObjectRepository;

class BookTest extends BaseApiTestCase
{
    private BookRepository|ObjectRepository $repository;

    public function getBookData($author): array
    {
        return [
            "isbn" => $this->faker->isbn13(),
            "name" => $this->faker->name(),
            "publicationDate" => $this->faker->date(),
            "country" => $this->faker->country(),
            "genre" => $this->faker->text(),
            "author" => $author
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getRepository(Book::class);
    }

    public function testCanCreateAnBook()
    {
        $initialCount = $this->repository->count([]);

        $bookData = $this->getBookData("/api/authors/1");

        $this->client->request("POST", "/api/books", ["json" => $bookData]);

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount+1, $finalCount);
    }

    public function testCanEditAnBook()
    {
        $book = $this->repository->find(1);

        $originalBook = clone $book;

        $bookData = $this->getBookData("/api/authors/2");

        $this->client->request("PUT", "/api/books/1", ["json" => $bookData]);
        self::assertResponseIsSuccessful();

        self::assertNotEquals($book->getIsbn(), $originalBook->getIsbn());
        self::assertNotEquals($book->getName(), $originalBook->getName());
        self::assertNotEquals($book->getPublicationDate(), $originalBook->getPublicationDate());
        self::assertNotEquals($book->getCountry(), $originalBook->getCountry());
        self::assertNotEquals($book->getGenre(), $originalBook->getGenre());
    }

    public function testDeleteAnBook()
    {
        $initialCount = $this->repository->count([]);

        $this->client->request("DELETE", "/api/books/2");

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount-1, $finalCount);
    }

}
