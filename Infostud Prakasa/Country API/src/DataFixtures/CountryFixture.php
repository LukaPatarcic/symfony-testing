<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\Country;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CountryFixture extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10,'main_country', function($i) use ($manager) {
           $country = new Country();
           $country->setName($this->faker->country)
               ->setSize($this->faker->numberBetween(10000,1000000))
               ->setCurrency($this->faker->word)
               ->setPopulation($this->faker->numberBetween(100000,100000000));
           return $country;
        });

        $manager->flush();
    }
}
