<?php

namespace App\Serializer\Normalizer;

use DateTime;
use App\Entity\Chimpokodex;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DateNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data = $this->normalizer->normalize($object, $format, $context);
        $data["date"]["server"] = new DateTime();

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        // TODO: return $data instanceof Object
        return $data instanceof Chimpokodex;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Chimpokodex::class => true];
    }
}
