<?php
namespace App\Constraints;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueFieldValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $entityRepository = $constraint->em->getRepository($constraint->class);

        if (!is_scalar($constraint->field)) {
            throw new InvalidArgumentException('"field" parameter should be any scalar type');
        }

        $entity = $entityRepository->findOneBy([
            $constraint->field => $value
        ]);

        if ($entity && $entity->getId() != $constraint->ignore_id) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}