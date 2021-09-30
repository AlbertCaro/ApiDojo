<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends BaseFixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 4; $i++) {
            $author = (new Author())
                ->setFullName($this->faker->name())
                ->setBirthday($this->faker->dateTime())
                ->setDateOfDeath($this->faker->dateTime())
                ->setNationality($this->faker->country());

            $manager->persist($author);
            $this->setReference("author-$i", $author);
        }

        $manager->flush();
    }
}
