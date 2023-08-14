<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/users/', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return $this->json($users);
    }

    #[Route('/users/{id<\d+>?}', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        return $this->json($user);
    }

    #[Route('/users/', methods: ['POST'])]
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
            ->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route('/users/{id<\d+>?}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['id'=> $id]);
        if (!$user) return $this->json(null, Response::HTTP_NOT_MODIFIED);
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

}