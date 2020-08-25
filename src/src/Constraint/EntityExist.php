<?php

namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

class EntityExist extends Constraint
{
    public $message = 'Entity does not exist.';
    public $property = 'id';
    public $class;

    public function getRequiredOptions()
    {
        return ['class'];
    }
}