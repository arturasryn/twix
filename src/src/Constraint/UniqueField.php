<?php

namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueField extends Constraint
{
    public $message = 'This value is already used.';
    public $class;
    public $field;
    public $em;
    public $ignore_id;

    public function getRequiredOptions()
    {
        return ['em', 'class', 'field'];
    }
}