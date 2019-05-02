<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixtures
{
    protected function loadData(ObjectManager $manager)
    {
      $this->createMany (User::class,5,function (User $user,$count){

          $user->setEmail ($this->faker->email);
          $user->setUsername ($this->faker->userName);
          $user->setPassword ($this->faker->password);
      });
      $manager->flush ();
    }

}