<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 4; $i++) {
            /** @var Author $author */
            $author = $this->getReference("author-$i");

            for ($f = 0; $f < $this->faker->numberBetween(1, 20); $f++) {
                $book = (new Book())
                    ->setIsbn($this->faker->isbn13())
                    ->setName($this->faker->name())
                    ->setPublicationDate($this->faker->dateTime())
                    ->setCountry($this->faker->country())
                    ->setGenre($this->faker->text())
                    ->setAuthor($author);

                $manager->persist($book);
            }
        }

        $manager->flush();
    }
}
