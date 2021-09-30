<?php

namespace App\Dto\Book;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Book;

#[ApiResource(
    shortName: "Book"
)]
class BookDto
{
    #[ApiProperty(readable: false)]
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
