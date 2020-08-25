<?php

namespace App\Request\User;

use App\Constraint\EntityExist;
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


class UpdateUserRequest extends BaseRequest
{
    private $user_id;
    private $request;

    protected const ALLOW_MISSING_FIELDS = true;

    public function __construct(RequestStack $r)
    {
        $this->request = $r->getCurrentRequest();
        $this->user_id = $this->request->get('id');

        parent::__construct($r);
    }

    protected function getFields() : array
    {
        $fields = [
            'id' => [new Required(), new EntityExist(['class' => User::class])]
        ];

        if($this->request->get('name')) {
            $rules['name'] = [new Length(['min' => 3, 'max' => 255])];
        }

        if($this->request->get('email')) {
            $rules['email'] = [
                new Length(['max' => 255]),
                new Email(),
                new UniqueField([
                    'class' => User::class,
                    'field' => 'email',
                    'ignore_id' => $this->user_id
                ])
            ];
        }

        if($this->request->get('password')) {
            $rules['password'] = [new Length(['min' => 6, 'max' => 255])];
        }

        if($this->request->get('sex')) {
            $rules['sex'] = [new HasValue(['values' => array_keys(User::SEX)])];
        }

        return $fields;
    }
}