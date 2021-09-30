<?php

namespace App\Dto\Author;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Author;

#[ApiResource(shortName: "Author")]
class AuthorDto
{
    #[ApiProperty(readable: false)]
    public ?int $id;

    public ?string $fullName;

    public ?\DateTime $birthday;

    public ?\DateTime $dateOfDeath;

    public ?string $nationality;

    public function __construct(Author $author = null)
    {
        if ($author) {
            $this->id = $author->getId();
            $this->fullName = $author->getFullName();
            $this->birthday = $author->getBirthday();
            $this->dateOfDeath = $author->getDateOfDeath();
            $this->nationality = $author->getNationality();
        }
    }
}
