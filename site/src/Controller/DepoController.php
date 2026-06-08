<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\CalendrierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DepoController extends AbstractController
{
    #[Route('/', name: 'app_depo')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $calendrier = new Calendrier();

        $form = $this->createForm(CalendrierType::class, $calendrier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $signature = $request->request->get('signature');

            $calendrier->setSignature($signature);

            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            $calendrier->setCreatedBy($this->getUser());

            $entityManager->persist($calendrier);
            $entityManager->flush();

            return $this->redirectToRoute('app_depo');
        }

        return $this->render('depo/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
