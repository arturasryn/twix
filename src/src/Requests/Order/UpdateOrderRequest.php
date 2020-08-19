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


class UpdateOrderRequest extends BaseRequest
{
    protected const ALLOW_MISSING_FIELDS = true;

    private $em;
    private $order_id;
    private $request;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
        $this->order_id = $this->request->get('id');

        parent::__construct($requestStack);
    }

    protected function getFields() : array
    {
        $fields = [
            'id' => [new Required(), new EntityExist(['em' => $this->em, 'class' => Order::class])]
        ];

        if($this->request->get('user_id')) {
            $fields['user_id'] = [new EntityExist(['em' => $this->em, 'class' => User::class])];
        }

        if($this->request->get('status')) {
            $fields['status'] = [new HasValue(['values' => array_keys(Order::STATUSES)])];
        }

        if($this->request->get('number')) {
            $fields['number'] = [new NotBlank(), new UniqueField([
                'em' => $this->em,
                'class' => Order::class,
                'field' => 'number',
                'ignore_id' => $this->order_id
            ])];
        }

        return $fields;
    }
}