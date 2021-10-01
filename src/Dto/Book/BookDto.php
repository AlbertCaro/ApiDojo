<?php

namespace App\Dto\Book;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Author\AuthorDto;
use App\Entity\Book;

#[ApiResource(
    shortName: "Book",
)]
class BookDto
{
    public ?int $id;

    public ?string $isbn;

    public ?string $name;

    public ?\DateTime $publicationDate;

    public ?string $country;

    public ?string $genre;

    public function __construct(Book $book = null)
    {
        if ($book) {
            $this->id = $book->getId();
            $this->isbn = $book->getIsbn();
            $this->name = $book->getName();
            $this->publicationDate = $book->getPublicationDate();
            $this->country = $book->getCountry();
            $this->genre = $book->getGenre();
        }
    }

}
