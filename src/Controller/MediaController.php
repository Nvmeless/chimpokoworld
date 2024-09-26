<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MediaController extends AbstractController
{

    #[Route('/', name: 'app_media')]
    public function index(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/media/{media}', name: 'app_media_get', methods: ['GET'])]
    public function getMedia(
        Media $media,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $publicPath = $media->getPublicPath();
        $location = $urlGenerator->generate("app_media", [], UrlGeneratorInterface::ABSOLUTE_URL);
        $location .= $publicPath . "/" . $media->getRealPath();
        $jsonMedia = $serializer->serialize($media, 'json');
        return $media ?
            new JsonResponse($jsonMedia, JsonResponse::HTTP_OK, [
                "Location" => $location
            ], true) :
            new JsonResponse(null, JsonResponse::HTTP_NOT_FOUND);

    }


    #[Route('/media', name: 'app_media_create', methods: ['POST'])]
    public function createMedia(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {

        $media = new Media();
        $file = $request->files->get('media');
        $media->setFile($file);
        $media->setPublicPath("uploads");
        $media->setDisplayName($file->getClientOriginalName());
        $media->setStatus("on");
        $entityManager->persist($media);
        $entityManager->flush();


        $jsonFile = $serializer->serialize($media, 'json');

        $location = $urlGenerator->generate('app_media_get', ['media' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonFile, JsonResponse::HTTP_CREATED, ['Location' => $location], true);
    }
}
