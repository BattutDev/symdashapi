<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;


    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/users/', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $responseData = array_map(function ($user) {
            return $user->toResponse();
        }, $users);
        return $this->json($responseData);
    }

    #[Route('/api/users/{id<\d+>?}', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        return $this->json($user?->toResponse());
    }

    #[Route('/api/users/', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (
            !isset($data['email'])
            || !isset($data['firstname'])
            || !isset($data['lastname'])
            || !isset($data['password']))
            return $this->json(['message' => 'Missing fields'], Response::HTTP_BAD_REQUEST);

        $user = new User();
        $user->setEmail($data['email'])
            ->setFirstName($data['firstname'])
            ->setLastName($data['lastname'])
            ->setPlainPassword($data['password']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user->toResponse(), Response::HTTP_CREATED);
    }

    #[Route('/api/users/{id<\d+>?}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['id'=> $id]);

        if (!$user) return $this->json(null, Response::HTTP_NOT_MODIFIED);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['firstname'])) {
            $user->setFirstName($data['firstname']);
        }

        if (isset($data['lastname'])) {
            $user->setLastName($data['lastname']);
        }

        if (isset($data['password'])) {
            $user->setPlainPassword($data['password']);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->json($user->toResponse());
    }


    #[Route('/api/users/{id<\d+>?}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id'=> $id]);
        if (!$user) return $this->json(null, Response::HTTP_NOT_MODIFIED);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/users/@me', methods: ['GET'])]
    public function getMe(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json($user->toResponse());
    }

}