<?php

namespace App\Encoder;

use App\Service\GestionMedia;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private readonly RequestStack $requestStack, private readonly GestionMedia $gestionMedia)
    {
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {

        $request = $this->requestStack->getCurrentRequest(); //dd($context);

        if (!$request) {
            return null;
        }

        $mediaFile = $request->files->get('media'); //dd($mediaFile);
        if ($mediaFile) {
            match ($context['groups']){
                "demandeur.write" => $media = $this->gestionMedia->upload($mediaFile, 'demandeur'),
                default => $media = $this->gestionMedia->upload($mediaFile, 'upload')
            }
            ;
        }

        return  array_map(static function (string $element) {
                // Multipart form values will be encoded in JSON.
                $decoded = json_decode($element, true);

                return \is_array($decoded) ? $decoded : $element;
                }, $request->request->all()) + ['media' => $media ?: ''];

    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}