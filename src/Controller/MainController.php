<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @Route("/api")
 */
class MainController extends AbstractController
{
    #[Route('/api/', name: 'app_main', methods: ['GET'])]
    ##[IsGranted('ROLE_ADMIN', message: 'Access Denied', statusCode: 403)]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Hello World'
        ]);
    }
}
