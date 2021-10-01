<?php

namespace App\Dto\User;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Contact;
use App\Entity\User;

#[ApiResource(
    shortName: "User",
)]
class UserDto
{
    #[ApiProperty(readable: false)]
    public ?int $id;

    public ?string $username;

    public ?string $password;

    public ?string $name = null;

    public ?string $lastName = null;

    public ?string $email = null;

    public ?string $phoneNumber = null;

    public ?string $address = null;

    public ?string $state = null;

    public ?string $city = null;

    public ?string $country = null;

    public ?string $postalCode = null;

    public function __construct(User $user = null, Contact $contact = null)
    {
        if ($user) {
            $this->id = $user->getId();
            $this->username = $user->getUsername();
            $this->password = $user->getPassword();
        }

        if ($contact) {
            $this->name = $contact->getName();
            $this->lastName = $contact->getLastName();
            $this->email = $contact->getEmail();
            $this->phoneNumber = $contact->getPhoneNumber();
            $this->address = $contact->getAddress();
            $this->state = $contact->getState();
            $this->city = $contact->getCity();
            $this->country = $contact->getCountry();
            $this->postalCode = $contact->getPostalCode();
        }
    }

    #[ApiProperty(readable: false)]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    #[ApiProperty]
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

}
