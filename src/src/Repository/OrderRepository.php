<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Order::class);
        $this->manager = $manager;
    }

    public function query()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id', 'desc')
            ->getQuery();
    }

    public function create(User $user, $params)
    {
        $order = new Order();

        if(!isset($params['number']) || !$params['number']) {
            $latestOrder = $this->findOneBy([], ['id' => 'desc']);
            $latestId = 0;
            if ($latestOrder)
            {
                $latestId = $latestOrder->getId();
            }

            $params['number'] = '#' . str_pad($latestId + 1, 8, "0", STR_PAD_LEFT);
        }

        $order->setParams($params)
              ->setUser($user);

        $this->manager->persist($user);
        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

    public function save($params)
    {
        $order = $this->findOrNew($params);
        if(!$order) return null;

        $order->setParams($params);

        $this->manager->persist($order);
        $this->manager->flush();

        return $order;
    }

    private function findOrNew($params)
    {
        if(isset($params['id']))
            return $this->find($params['id']);

        return new Order();
    }

    public function delete($id)
    {
        $order = $this->find($id);

        if($order) {
            $this->manager->remove($order);
            $this->manager->persist($order->getUser());
            $this->manager->flush();
            return true;
        }

        return false;
    }
    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
