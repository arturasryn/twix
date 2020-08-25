<?php

namespace App\Request\User;

use App\Constraint\HasValue;
use App\Constraint\UniqueField;
use App\Entity\User;
use App\Request\BaseRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\
{
    Email, Length, Required
};


class CreateUserRequest extends BaseRequest
{
    protected function getFields() : array
    {
        return [
            'name'   => [new Required(), new Length(['min' => 3, 'max' => 255])],
            'email' => [new Required(), new Length(['max' => 255]), new Email(), new UniqueField(['class' => User::class, 'field' => 'email'])],
            'password' => [new Required(), new Length(['min' => 6, 'max' => 255])],
            'sex' => [new Required(), new HasValue(['values' => array_keys(User::SEX)])]
        ];
    }
}