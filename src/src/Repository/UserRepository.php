<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;
    private $userPasswordEncoder;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function query()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'desc')
            ->getQuery();
    }

    public function save($params)
    {
        $user = $this->findOrNew($params);
        if(!$user) return null;

        if(isset($params['password'])) {
            $params['password'] = $this->userPasswordEncoder->encodePassword($user,$params['password']);
        }

        $user->setParams($params);

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);

        if($user) {
            $this->manager->remove($user);
            $this->manager->flush();
            return true;
        }

        return false;
    }

    private function findOrNew($params)
    {
        if(isset($params['id']))
            return $this->find($params['id']);

        return new User();
    }

    public function orders($id) {
        $user = $this->find($id);

//        dump($user->getOrders());
//        die();

        if($user)
            return $user->getOrders()->getValues();

        return null;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
