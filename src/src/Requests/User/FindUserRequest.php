<?php

namespace App\Requests\User;

use App\Constraints\EntityExist;
use App\Entity\User;
use App\Requests\BaseValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    Required
};


class FindUserRequest extends BaseValidation
{
    private $em;

    public function __construct(RequestStack $r, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($r);
    }

    protected function getFields() : array
    {
        return [
            'id'   => [new Required(), new EntityExist(['em' => $this->em, 'class' => User::class])]
        ];
    }
}