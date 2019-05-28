<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    public function search($params, $page = 1, $pageSize = 10)
    {
        $qb = $this->createQueryBuilder('profile');
        $fieldsToCheck = ['pib','maticniBroj', 'address', 'phoneNumber', 'description', 'email'];
        foreach ($fieldsToCheck as $field) {
            if (array_key_exists($field, $params)) {
                $qb->andWhere('profile.'.$field.' = :'.$field.'Value');
                $qb->setParameter($field.'Value', $params[$field]);
            }
        }
        $qb
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);
        $paginator = new Paginator($qb);
        $data = $paginator->getIterator();
        return [
            'data' => $data,
            'total' => $paginator->count(),
            'totalPages' => ceil($paginator->count() / $pageSize),
            'page' => (int) $page,
            'pageSize' => (int) $pageSize,
            'numberOfRecords' => count($data),
        ];
    }

    public function getInfostudProfiles()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name = :profileName')
            ->setParameter('profileName','Infostud')
            ->getQuery()
            ->getResult();
    }

}
