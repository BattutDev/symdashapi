<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AccessDeniedHttpException) {
            // Créez une réponse personnalisée
            $response = new JsonResponse([
                'code' => 403,
                'message' => 'Access Denied'
            ], Response::HTTP_FORBIDDEN);

            $event->setResponse($response);
        }
    }
}