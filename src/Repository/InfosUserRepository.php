<?php

namespace App\Repository;

use App\Entity\InfosUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InfosUser>
 *
 * @method InfosUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method InfosUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method InfosUser[]    findAll()
 * @method InfosUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfosUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InfosUser::class);
    }

    /**
     * @return InfosUser[] Returns an array of InfosUser objects
     */
    public function findAllInfosUser(): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.age >= 40 and i.age <= 50')
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function findInfosUserById($id): ?InfosUser
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    public function findOneBySomeField($value): ?InfosUser
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
