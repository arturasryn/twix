<?php

namespace App\Request\Order;

use App\Constraint\EntityExist;
use App\Constraint\HasValue;
use App\Constraint\UniqueField;
use App\Entity\Order;
use App\Entity\User;
use App\Request\BaseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    NotBlank, Required
};


class CreateOrderRequest extends BaseRequest
{
    protected const ALLOW_MISSING_FIELDS = true;

    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        parent::__construct($requestStack);
    }

    protected function getFields() : array
    {
        $fields = [
            'user_id' => [new Required(), new EntityExist(['class' => User::class])],
            'status' => [new Required(), new HasValue( ['values' => array_keys(Order::STATUSES)])]
        ];

        if($this->request->get('number')) {
            $fields['number'] = [new NotBlank(), new UniqueField(['class' => Order::class, 'field' => 'number'])];
        }

        return $fields;
    }
}