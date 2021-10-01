<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Test\BaseApiTestCase;
use Doctrine\Persistence\ObjectRepository;

class UserTest extends BaseApiTestCase
{
    private UserRepository|ObjectRepository $repository;

    public function getUserData(): array
    {
        return [
            "username" => $this->faker->userName(),
            "password" => $this->faker->password(),
            "name" => $this->faker->name(),
            "lastName" => $this->faker->lastName(),
            "email" => $this->faker->email(),
            "phoneNumber" => $this->faker->phoneNumber(),
            "address" => $this->faker->address(),
            "state" => $this->faker->city(),
            "city" => $this->faker->city(),
            "country" => $this->faker->country(),
            "postalCode" => $this->faker->postcode(),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->getRepository(User::class);
    }

    public function testCanCreateAnUser()
    {
        $initialCount = $this->repository->count([]);

        $userData = $this->getUserData();

        $this->client->request("POST", "/api/users", ["json" => $userData]);

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount+1, $finalCount);
    }

    public function testCanEditAnUser()
    {
        $user = $this->repository->find(1);

        $originalUser = clone $user;
        $originalContact = clone $user->getContact();

        $userData = $this->getUserData();

        $this->client->request("PUT", "/api/users/1", ["json" => $userData]);
        self::assertResponseIsSuccessful();

        self::assertNotEquals($user->getUsername(), $originalUser->getUsername());
        self::assertNotEquals($user->getPassword(), $originalUser->getPassword());
        self::assertNotEquals($user->getContact()->getName(), $originalContact->getName());
        self::assertNotEquals($user->getContact()->getLastName(), $originalContact->getLastName());
        self::assertNotEquals($user->getContact()->getEmail(), $originalContact->getEmail());
        self::assertNotEquals($user->getContact()->getPhoneNumber(), $originalContact->getPhoneNumber());
        self::assertNotEquals($user->getContact()->getAddress(), $originalContact->getAddress());
        self::assertNotEquals($user->getContact()->getState(), $originalContact->getState());
        self::assertNotEquals($user->getContact()->getCity(), $originalContact->getCity());
        self::assertNotEquals($user->getContact()->getCountry(), $originalContact->getCountry());
        self::assertNotEquals($user->getContact()->getPostalCode(), $originalContact->getPostalCode());
    }

    public function testDeleteAnUser()
    {
        $initialCount = $this->repository->count([]);

        $this->client->request("DELETE", "/api/users/2");

        $finalCount = $this->repository->count([]);

        self::assertResponseIsSuccessful();
        self::assertEquals($initialCount-1, $finalCount);
    }

}
