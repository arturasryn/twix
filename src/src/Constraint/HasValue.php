<?php

namespace App\Constraint;

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