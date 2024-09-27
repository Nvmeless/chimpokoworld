<?php

namespace App\Controller;

use App\Entity\Chimpokomon;
use App\Repository\ChimpokodexRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ChimpokomonRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChimpokomonController extends AbstractController
{
    #[Route('/api/chimpokomon', name: 'app_chimpokomon_getAll', methods: ['GET'])]
    public function getAllChimpokomons(
        ChimpokomonRepository $chimpokomonRepository,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $idCache = "getAllChimpokomons";
        $cachedChimpokos = $cache->get($idCache, function (ItemInterface $item) use ($chimpokomonRepository, $serializer) {
            $item->tag("chimpokomonCache");
            echo "Mise en cache";
            $chimpokolist = $chimpokomonRepository->findStatusOn();
            $jsonChimpokos = $serializer->serialize($chimpokolist, 'json', ['groups' => "chimpokomon"]);
            return $jsonChimpokos;
        });




        // $jsonChimpokos = $serializer->serialize($chimpokomonRepository->findStatusOn(), 'json', ["groups" => "chimpokomon"]);
        return new JsonResponse($cachedChimpokos, Response::HTTP_OK, [], true);
        // return $this->json($jsonChimpokos);
    }


    #[Route('/api/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_get', methods: ['GET'])]
    public function getChimpokomon(
        Chimpokomon $chimpokomon
    ): JsonResponse {
        return $this->json($chimpokomon);
    }



    #[Route('/api/chimpokomon', name: 'app_chimpokon_create', methods: ["POST"])]
    public function createChimpokomon(
        Request $request,
        EntityManagerInterface $entityManager,
        TagAwareCacheInterface $cache,
        UrlGeneratorInterface $urlGenerator,
        ChimpokodexRepository $chimpokodexRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        $requestData = $request->toArray();
        $newChimpo = $serializer->deserialize($request->getContent(), Chimpokomon::class, "json");
        $newChimpo->setStatus('on');
        $chimpokodex = $chimpokodexRepository->find($requestData["chimpokodexId"]);
        $newChimpo->setChimpokodex($chimpokodex ?? null);
        if ($requestData["pvMax"] > $chimpokodex->getPvMax()) {
            $newChimpo->setPvMax($chimpokodex->getPvMax());
        }
        $entityManager->persist($newChimpo);
        $entityManager->flush();
        $cache->invalidateTags(['chimpokomonCache']);
        $jsonChimpokomon = $serializer->serialize($newChimpo, 'json', ['groups' => 'chimpokomon']);

        $location = $urlGenerator->generate('app_chimpokomon_get', ['chimpokomon' => $newChimpo->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonChimpokomon, JsonResponse::HTTP_CREATED, ["Location" => $location], true);
        // return $this->json();
    }

    #[Route('/api/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_update', methods: ['PUT', 'PATCH'])]
    public function updateChimpokomon(
        Chimpokomon $chimpokomon,
        Request $request,
        EntityManagerInterface $entityManager,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer,
    ): JsonResponse {

        $requestData = $request->toArray();
        $newChimpo = $serializer->deserialize($request->getContent(), Chimpokomon::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $chimpokomon]);
        $newChimpo->setStatus('on');
        $chimpokodex = $newChimpo->getChimpokodex();
        if ($requestData["pvMax"] > $chimpokodex->getPvMax()) {
            $newChimpo->setPvMax($chimpokodex->getPvMax());
        }
        $entityManager->persist($newChimpo);
        $entityManager->flush();
        $cache->invalidateTags(['chimpokomonCache']);


        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

    }




    #[Route('/api/chimpokomon/{chimpokomon}', name: 'app_chimpokomon_delete', methods: ['DELETE'])]
    public function deleteChimpokomon(
        Chimpokomon $chimpokomon,
        EntityManagerInterface $entityManager,
        Request $request,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $force = $request->toArray()["force"] ?? false;
        if ($force) {
            $entityManager->remove($chimpokomon);

        } else {
            $chimpokomon->setStatus('off');
            $entityManager->persist($chimpokomon);
        }
        $entityManager->flush();
        $cache->invalidateTags(['chimpokomonCache']);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
