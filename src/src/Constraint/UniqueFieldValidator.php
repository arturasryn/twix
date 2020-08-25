<?php
namespace App\Constraint;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Facade\Facade;

class UniqueFieldValidator extends ConstraintValidator
{

    private $em;

    public function __construct()
    {
        $this->em = Facade::get('doctrine.orm.entity_manager');
    }

    public function validate($value, Constraint $constraint)
    {
        $entityRepository = $this->em->getRepository($constraint->class);

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