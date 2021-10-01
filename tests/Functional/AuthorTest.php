<?php

namespace App\Tests\Functional;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Test\BaseApiTestCase;
use Doctrine\Persistence\ObjectRepository;

class AuthorTest extends BaseApiTestCase
{
    private AuthorRepository|ObjectRepository $repository;

    public function getAuthorData(): array
    {
        return [
            "fullName" => $this->faker->name(),
            "birthday" => $this->faker->date(),
            "dateOfDeath" => $this->faker->date(),
            "nationality" => $this->faker->country(),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getRepository(Author::class);
    }

    public function testCanCreateAnAuthor()
    {
        $initialCount = $this->repository->count([]);

        $authorData = $this->getAuthorData();

        $this->client->request("POST", "/api/authors", ["json" => $authorData]);

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount+1, $finalCount);
    }

    public function testCanEditAnAuthor()
    {
        $author = $this->repository->find(1);

        $originalAuthor = clone $author;

        $authorData = $this->getAuthorData();

        $this->client->request("PUT", "/api/authors/1", ["json" => $authorData]);
        self::assertResponseIsSuccessful();

        self::assertNotEquals($author->getFullName(), $originalAuthor->getFullName());
        self::assertNotEquals($author->getBirthday(), $originalAuthor->getBirthday());
        self::assertNotEquals($author->getDateOfDeath(), $originalAuthor->getDateOfDeath());
        self::assertNotEquals($author->getNationality(), $originalAuthor->getNationality());
    }

    public function testDeleteAnAuthor()
    {
        $initialCount = $this->repository->count([]);

        $this->client->request("DELETE", "/api/authors/2");

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount-1, $finalCount);
    }

}
