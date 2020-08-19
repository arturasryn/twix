<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

class EntityExist extends Constraint
{
    public $message = 'Entity does not exist.';
    public $property = 'id';
    public $class;
    public $em;

    public function getRequiredOptions()
    {
        return ['em', 'class'];
    }
}