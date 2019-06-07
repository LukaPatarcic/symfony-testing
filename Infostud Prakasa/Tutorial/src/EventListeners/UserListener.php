<?php


namespace App\EventListeners;


use App\Entity\Profile;
use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UserListener
{
    /**
     * @param User $user
     * @param LifecycleEventArgs $args
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     */
    public function postPersist(User $user, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $profile = new Profile();
        $profile->setUser($user)
            ->setEmail($user->getEmail())
            ->setName('ProfileName#Creative');
        $em->persist($profile);
        $em->flush($profile);
    }
}