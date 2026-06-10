<?php

namespace App\Controller;

use App\Entity\Tournee;
use App\Repository\CalendrierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        UserRepository $userRepository,
        CalendrierRepository $calendrierRepository,
        EntityManagerInterface $em
    ): Response {
        $users = $userRepository->findAll();

        $totalCalendriers = $calendrierRepository->createQueryBuilder('c')
            ->select('SUM(c.nombreCalendrier)')
            ->getQuery()
            ->getSingleScalarResult();

        $totalArgent = $calendrierRepository->createQueryBuilder('c')
            ->select('SUM(c.argentRecolte)')
            ->getQuery()
            ->getSingleScalarResult();

        $lastCalendriers = $calendrierRepository->findBy([], [
            'createdAt' => 'DESC'
        ], 10);

        $tournees = $em->createQueryBuilder()
            ->select('t.id, t.ville, t.codePostal')
            ->addSelect('COALESCE(SUM(c.argentRecolte), 0) AS totalArgent')
            ->addSelect('COALESCE(SUM(c.nombreCalendrier), 0) AS totalCalendriers')
            ->from(Tournee::class, 't')
            ->leftJoin('t.calendriers', 'c')
            ->groupBy('t.id')
            ->orderBy('t.ville', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'lastCalendriers' => $lastCalendriers,
            'totalCalendriers' => $totalCalendriers ?? 0,
            'totalArgent' => $totalArgent ?? 0,
            'tournees' => $tournees,
        ]);
    }
}
