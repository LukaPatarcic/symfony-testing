<?php

namespace App\DataFixtures;

use App\Entity\TransactionType;
use Doctrine\Common\Persistence\ObjectManager;

class TransactionTypeFixtures extends BaseFixtures
{

    private static $type = [
        'income',
        'outcome',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany (TransactionType::class,10,function(TransactionType $transactionType,$count){

            $transactionType->setType ($this->faker->randomElement (self::$type));
            $transactionType->setName ($this->faker->word);

        });
        $manager->flush();
    }

}
