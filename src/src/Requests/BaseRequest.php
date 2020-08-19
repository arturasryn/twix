<?php

namespace App\Requests;

use App\Exception\ValidationException;
use App\Traits\ValidationAwareTrait;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

class BaseRequest
{
    use ValidationAwareTrait;

    protected const ALLOW_EXTRA_FIELDS = true;
    protected const ALLOW_MISSING_FIELDS = false;
    protected const EXTRA_FIELDS_MESSAGE = 'This field was not expected.';
    protected const MISSING_FIELDS_MESSAGE = 'This field is missing.';


    private $validator;

    public function __construct(RequestStack $request)
    {
        $this->httpRequest = $request->getCurrentRequest();
        $this->validator = Validation::createValidator();

        $this->initialize();
    }

    final public function initialize() : void
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $this->validate();
    }

    /**
     * Get all request parameters
     *
     * @return array
     */
    final public function all() : array
    {
        return $this->httpRequest->attributes->all()
            + $this->httpRequest->query->all()
            + $this->httpRequest->request->all()
            + $this->httpRequest->files->all();
    }

    /**
     * Returns list of constraints for validation
     *
     * @return Collection
     */
    public function rules() : Collection
    {
        return new Collection([
            'fields'                => $this->getFields(),
            'allowExtraFields'      => self::ALLOW_EXTRA_FIELDS,
            'allowMissingFields'    => self::ALLOW_MISSING_FIELDS,
            'extraFieldsMessage'    => self::EXTRA_FIELDS_MESSAGE,
            'missingFieldsMessage'  => self::MISSING_FIELDS_MESSAGE,
        ]);
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    final protected function passesAuthorization() : bool
    {
        if (method_exists($this, 'authorize')) {
            return $this->authorize();
        }

        return true;
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     * @throws AccessDeniedHttpException
     */
    final protected function failedAuthorization() : void
    {
        throw new AccessDeniedHttpException();
    }

    /**
     * @return bool
     * @throws ValidationException
     */
    final protected function validate() : bool
    {
        /** @var ConstraintViolationList $violations */
        $violations = $this->validator->validate($this->all(), $this->rules());

        if ($violations->count()) {
            throw new ValidationException($this->validator, $violations);
        }

        return true;
    }
}