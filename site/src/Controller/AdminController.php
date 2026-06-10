<?php

namespace App\Controller;

use App\Entity\Tournee;
use App\Entity\User;
use App\Repository\CalendrierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/admin/tournee/create', name: 'admin_create_tournee', methods: ['POST'])]
public function createTournee(
    Request $request,
    EntityManagerInterface $em
): Response {
    $ville = $request->request->get('ville');
    $codePostal = $request->request->get('codePostal');

    if ($ville && $codePostal) {
        $tournee = new Tournee();
        $tournee->setVille($ville);
        $tournee->setCodePostal($codePostal);

        $em->persist($tournee);
        $em->flush();
    }

    return $this->redirectToRoute('app_admin');
}

#[Route('/admin/user/{id}/delete', name: 'admin_delete_user', methods: ['POST'])]
public function deleteUser(
    User $user,
    EntityManagerInterface $em
): Response {
    foreach ($user->getCalendriers() as $calendrier) {
        $em->remove($calendrier);
    }

    $em->remove($user);
    $em->flush();

    return $this->redirectToRoute('app_admin');
}

#[Route('/admin/calendriers/reset', name: 'admin_reset_calendriers', methods: ['POST'])]
public function resetCalendriers(
    CalendrierRepository $calendrierRepository,
    EntityManagerInterface $em
): Response {
    $calendriers = $calendrierRepository->findAll();

    foreach ($calendriers as $calendrier) {
        $em->remove($calendrier);
    }

    $em->flush();

    return $this->redirectToRoute('app_admin');
}
}
