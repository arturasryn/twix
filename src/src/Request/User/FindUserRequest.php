<?php

namespace App\Request\User;

use App\Constraint\EntityExist;
use App\Entity\User;
use App\Request\BaseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    Required
};


class FindUserRequest extends BaseRequest
{
    protected function getFields() : array
    {
        return [
            'id'   => [new Required(), new EntityExist(['class' => User::class])]
        ];
    }
}