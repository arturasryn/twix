<?php

namespace App\Request\Order;

use App\Constraint\EntityExist;
use App\Entity\Order;
use App\Request\BaseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    Required
};


class FindOrderRequest extends BaseRequest
{
    protected const ALLOW_MISSING_FIELDS = true;

    private $em;

    public function __construct(RequestStack $r, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($r);
    }

    protected function getFields() : array
    {
        return [
            'id'   => [new Required(), new EntityExist(['em' => $this->em, 'class' => Order::class])]
        ];
    }
}