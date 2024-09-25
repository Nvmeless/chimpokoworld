<?php

namespace App\Controller;

use App\Entity\Chimpokomon;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChimpokomonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChimpokomonController extends AbstractController
{


    #[Route('/chimpokomon', name: 'app_chimpokomon_getAll')]
    public function getAllChimpokomons(
        ChimpokomonRepository $chimpokomonRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $jsonChimpokos = $serializer->serialize($chimpokomonRepository->findStatusOn(), 'json',["groups" => "chimpokomon"]);
        return new JsonResponse($jsonChimpokos, Response::HTTP_OK, [], true);
        // return $this->json($jsonChimpokos);
    }


    #[Route('/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_get', methods:['GET'])]
    public function getChimpokomon(
        Chimpokomon $chimpokomon
    ): JsonResponse
    {
        return $this->json($chimpokomon);
    }



    #[Route('/chimpokomon', name:'app_chimpokon_create', methods:["POST"])]
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

    #[Route('/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_update', methods:['PUT', 'PATCH'])]
      public function updateChimpokomon(
        Chimpokomon $chimpokomon,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {


        $chimpokomon->setName($request->toArray()['name'] ?? $chimpokomon->getName());
        $entityManager->persist($chimpokomon);
        $entityManager->flush();
        return $this->json($chimpokomon);
    }




    #[Route('/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_delete', methods:['DELETE'])]
    public function deleteChimpokomon(
        Chimpokomon $chimpokomon,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse
    {
        $force = $request->toArray()["force"] ?? false;
        if($force){
            $entityManager->remove($chimpokomon);

        }else{
            $chimpokomon->setStatus('off');
            $entityManager->persist($chimpokomon);
        }

        $entityManager->flush();

        return $this->json(null);
    }

}
