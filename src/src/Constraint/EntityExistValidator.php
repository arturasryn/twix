<?php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Facade\Facade;

class EntityExistValidator extends ConstraintValidator
{
    private $em;

    public function __construct()
    {
        $this->em = Facade::get('doctrine.orm.entity_manager');
    }

    public function validate($value, Constraint $constraint): void
    {
        if (empty($value)) {
            return;
        }

        if (!$constraint instanceof EntityExist) {
            throw new \LogicException(\sprintf('You can only pass %s constraint to this validator.', EntityExist::class));
        }

        if (empty($constraint->class)) {
            throw new \LogicException(\sprintf('Must set "entity" on "%s" validator', EntityExist::class));
        }

        $data = $this->em->getRepository($constraint->class)->findOneBy([
            $constraint->property => $value,
        ]);

        if (null === $data) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}