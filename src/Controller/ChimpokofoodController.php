<?php

namespace App\Controller;

use App\Entity\Chimpokofood;
use App\Form\ChimpokofoodType;
use App\Repository\ChimpokofoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chimpokofood')]
final class ChimpokofoodController extends AbstractController
{
    #[Route(name: 'app_chimpokofood_index', methods: ['GET'])]
    public function index(ChimpokofoodRepository $chimpokofoodRepository): Response
    {
        return $this->render('chimpokofood/index.html.twig', [
            'chimpokofoods' => $chimpokofoodRepository->findAll(),
        ]);
    }

    #[Route(name: 'app_chimpokofood_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chimpokofood = new Chimpokofood();
        $form = $this->createForm(ChimpokofoodType::class, $chimpokofood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chimpokofood);
            $entityManager->flush();

            return $this->redirectToRoute('app_chimpokofood_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chimpokofood/new.html.twig', [
            'chimpokofood' => $chimpokofood,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chimpokofood_show', methods: ['GET'])]
    public function show(Chimpokofood $chimpokofood): Response
    {
        return $this->render('chimpokofood/show.html.twig', [
            'chimpokofood' => $chimpokofood,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chimpokofood_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chimpokofood $chimpokofood, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChimpokofoodType::class, $chimpokofood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chimpokofood_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chimpokofood/edit.html.twig', [
            'chimpokofood' => $chimpokofood,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chimpokofood_delete', methods: ['POST'])]
    public function delete(Request $request, Chimpokofood $chimpokofood, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chimpokofood->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chimpokofood);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chimpokofood_index', [], Response::HTTP_SEE_OTHER);
    }
}
