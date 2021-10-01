<?php

namespace App\Helper;

use App\Dto\User\UserDto;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserDtoHelper
{
    private EntityManagerInterface $entityManager;

    private UserRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function fetchAll(): array
    {
        $users = $this->repository->findAll();

        return array_map(fn(User $user) => new UserDto($user, $user->getContact()), $users);
    }

    public function fetchOne($id): ?UserDto
    {
        $user = $this->repository->find($id);

        if ($user) {
            return new UserDto($user, $user->getContact());
        }

        return null;
    }

    public function persist(User $user, Contact $contact, UserDto $dto): UserDto
    {
        $user->setDataFromDto($dto);
        $contact->setDataFromDto($dto);

        if (!$this->entityManager->contains($user)) {
            $this->entityManager->persist($user);
        }

        if (!$this->entityManager->contains($contact)) {
            $this->entityManager->persist($contact);
        }

        $this->entityManager->flush();
        $this->entityManager->refresh($user);

        return new UserDto($user);
    }
}
