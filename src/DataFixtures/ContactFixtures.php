<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends BaseFixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 11; $i++) {
            $contact = (new Contact())
                ->setName($this->faker->name())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setPhoneNumber($this->faker->phoneNumber())
                ->setAddress($this->faker->address())
                ->setState($this->faker->city())
                ->setCountry($this->faker->city())
                ->setCity($this->faker->city())
                ->setPostalCode($this->faker->postcode());

            $manager->persist($contact);
            $this->setReference("contact-$i", $contact);
        }

        $manager->flush();
    }
}
