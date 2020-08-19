<?php

namespace App\Requests\Order;

use App\Constraints\EntityExist;
use App\Constraints\HasValue;
use App\Constraints\UniqueField;
use App\Entity\Order;
use App\Entity\User;
use App\Requests\BaseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    NotBlank, Required
};


class CreateOrderRequest extends BaseRequest
{
    protected const ALLOW_MISSING_FIELDS = true;

    private $em;
    private $request;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
        parent::__construct($requestStack);
    }

    protected function getFields() : array
    {
        $fields = [
            'user_id' => [new Required(), new EntityExist(['em' => $this->em, 'class' => User::class])],
            'status' => [new Required(), new HasValue( ['values' => array_keys(Order::STATUSES)])]
        ];

        if($this->request->get('number')) {
            $fields['number'] = [new NotBlank(), new UniqueField(['em' => $this->em, 'class' => Order::class, 'field' => 'number'])];
        }

        return $fields;
    }
}