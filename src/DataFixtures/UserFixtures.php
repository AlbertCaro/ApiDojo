<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixture implements DependentFixtureInterface
{

    public function getDependencies(): array
    {
        return [
            ContactFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < $this->faker->numberBetween(1, 11); $i++) {
            /** @var Contact $contact */
            $contact = $this->getReference("contact-$i");

            $user = (new User())
                ->setUsername($this->faker->userName())
                ->setPassword($this->faker->password())
                ->setContact($contact);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
