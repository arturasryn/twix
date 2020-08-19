<?php
namespace App\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasValueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (empty($value)) {
            return;
        }

        if (!$constraint instanceof HasValue) {
            throw new \LogicException(\sprintf('You can only pass %s constraint to this validator.', HasValue::class));
        }

        if (empty($constraint->values)) {
            throw new \LogicException(\sprintf('Must set "values" on "%s" validator', HasValue::class));
        }

        if(!in_array($value, $constraint->values)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}