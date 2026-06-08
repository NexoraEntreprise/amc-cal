<?php

namespace App\Controller;

use App\Repository\CalendrierRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(
        CalendrierRepository $calendrierRepository,
        UserRepository $userRepository
    ): Response {
        return $this->render('admin/index.html.twig', [
            'totalCalendriers' => $calendrierRepository->getTotalCalendriers(),
            'totalArgent' => $calendrierRepository->getTotalArgent(),
            'users' => $userRepository->findAllWithHistory(),
            'lastCalendriers' => $calendrierRepository->getLastCalendriers(10),
        ]);
    }
}