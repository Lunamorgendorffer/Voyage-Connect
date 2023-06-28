<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }


    public function getTrendUsers(){

        /*$queryBuilder = $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.user', 'u');

            if (!empty($searchData->q)) {
                $queryBuilder
                    ->andWhere('p.title LIKE :q')
                    ->orWhere('u.pseudo LIKE :q')
                    ->setParameter('q', "%{$searchData->q}%");
            }

        return $queryBuilder->getQuery()->getResult(); */

        

        $query = $entityManager->createQuery('
        SELECT DISTINCT u.pseudo, p.title
        FROM App\Entity\Post p 
        INNER JOIN p.likes l
        INNER JOIN App\Entity\User u
        WHERE p.id IN (
            SELECT p2.id
            FROM App\Entity\Post p2
            INNER JOIN p2.likes l2
            GROUP BY p2.id
            HAVING COUNT(l2) >= 2
        )')
        ->select('u.pseudo, p.title')
        ->from ('App\Entity\Post', 'p')
        ->innerJoin('p.likes', 'l')
        ->innerJoin('App\Entity\User', 'u')
        ->inWhere('p.id','')
        ;
    
    $results = $query->getResult();
    

        



    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
