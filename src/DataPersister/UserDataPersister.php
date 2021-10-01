<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\User\UserDto;
use App\Entity\Contact;
use App\Entity\User;
use App\Helper\UserDtoHelper;
use Doctrine\ORM\EntityManagerInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private UserDtoHelper $helper;

    private EntityManagerInterface $entityManager;

    public function __construct(UserDtoHelper $helper, EntityManagerInterface $entityManager)
    {
        $this->helper = $helper;
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof UserDto;
    }

    public function persist($data, array $context = []): UserDto
    {
        $operation = $context['item_operation_name'] ?? $context['collection_operation_name'];

        switch ($operation) {
            case 'put':
                $user = $this->entityManager->find(User::class, $data->id);
                $contact = $user->getContact();
                break;
            case 'post':
                $user = new User();
                $contact = new Contact();
                break;
            default:
                throw new \RuntimeException("Invalid HTTP method");
        }

        return $this->helper->persist($user, $contact, $data);
    }

    public function remove($data, array $context = [])
    {
        $user = $this->entityManager->find(User::class, $data->id);

        if ($user) {
            $this->entityManager->remove($user->getContact());
            $this->entityManager->remove($user);

            $this->entityManager->flush();
        }
    }
}
