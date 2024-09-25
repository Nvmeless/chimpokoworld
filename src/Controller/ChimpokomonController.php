<?php

namespace App\Controller;

use App\Entity\Chimpokomon;
use App\Repository\ChimpokomonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChimpokomonController extends AbstractController
{


    // #[Route('/chimpokomon', name: 'app_chimpokomon')]
    // public function index(): JsonResponse
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/ChimpokomonController.php',
    //     ]);
    // }


    #[Route('/chimpokomon', name: 'app_chimpokomon')]
    public function getAllChimpokomons(
        ChimpokomonRepository $chimpokomonRepository
    ): JsonResponse
    {
        $jsonChimpokos = $chimpokomonRepository->findAll();
        return $this->json($jsonChimpokos);
    }


    #[Route('/chimpokomon/{chimpokomon}', name: 'app_chimpokomon', methods:['GET'])]
    public function getChimpokomon(
        Chimpokomon $chimpokomon
    ): JsonResponse
    {
        return $this->json($chimpokomon);
    }



    #[Route('/chimpokomon/new', name:'app_chimpokon_new', methods:["POST"])]
      public function createChimpokomon(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $requestData = $request->toArray();
        $newChimpo = new Chimpokomon();
        $newChimpo->setName($requestData["name"]);
        $newChimpo->setStatus('on');
        $entityManager->persist($newChimpo);
        $entityManager->flush();
        return $this->json($newChimpo);
    }
    #[Route('/chimpokomon/{chimpokomon}', name: 'app_chimpokomon', methods:['DELETE'])]
    public function deleteChimpokomon(
        Chimpokomon $chimpokomon,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $entityManager->remove($chimpokomon);
        $entityManager->flush();

        return $this->json(null);
    }




}
