<?php

namespace App\Listeners;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener {

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ( ! $exception instanceof ValidationException)
        {
            return;
        }

        $event->setResponse(new JsonResponse($exception->getResponseData(), 400));
    }
}