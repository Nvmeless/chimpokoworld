<?php

namespace App\Serializer\Normalizer;

use DateTime;
use App\Entity\Chimpokodex;
use App\Entity\Chimpokomon;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {




        $data = $this->normalizer->normalize($object, $format, $context);
        $className = (new \ReflectionClass($object))->getShortName();
        $className = strtolower($className);
        $links = [
            'all' => $this->urlGenerator->generate("app_" . $className . "_getAll"),
        ];

        $data['_links'] = $links;
        // $data = $this->normalizer->normalize($object, $format, $context);
        // $data["date"]["server"] = new DateTime();

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        // TODO: return $data instanceof Object
        return $data instanceof Chimpokodex || $data instanceof Chimpokomon;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Chimpokodex::class => true, Chimpokomon::class => true];
    }
}
