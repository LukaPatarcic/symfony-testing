<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CityFixture extends BaseFixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(20,'main_city', function($i) use ($manager) {
           $city = new City();
           $country = $this->getRandomReference('main_country');
           $city->setName($this->faker->city)
               ->setZipCode($this->faker->numberBetween(100,10000))
               ->setCountry($country)
               ->setPopulation($this->faker->numberBetween(100000,100000000));
           return $city;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          CountryFixture::class,
        ];
    }


}
