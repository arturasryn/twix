<?php

namespace App\Constraints;

use Symfony\Component\Validator\Constraint;

class HasValue extends Constraint
{
    public $message = 'Value not accepted.';
    public $values;

    public function getRequiredOptions()
    {
        return ['values'];
    }
}