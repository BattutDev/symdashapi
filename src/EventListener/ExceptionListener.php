<?php

namespace App\EventListener;

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        /*
        if ($exception instanceof AccessDeniedHttpException) {
            // Créez une réponse personnalisée
            $response = new JsonResponse([
                'code' => 403,
                'message' => 'Access Denied'
            ], Response::HTTP_FORBIDDEN);

            $event->setResponse($response);
        }
        else
        if ($exception instanceof NotFoundHttpException) {
            // Créez une réponse personnalisée
            $response = new JsonResponse([
                'code' => 404,
                'message' => 'Route not found'
            ], Response::HTTP_NOT_FOUND);

            $event->setResponse($response);
        }
        else
        if ($exception instanceof InternalErrorException) {
            // Créez une réponse personnalisée
            $response = new JsonResponse([
                'code' => 500,
                'message' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

            $event->setResponse($response);
        }
        */
        $event->setResponse(new JsonResponse([
            'code' => $exception->getStatusCode(),
            'message' => $exception->getMessage()
        ]));


    }
}