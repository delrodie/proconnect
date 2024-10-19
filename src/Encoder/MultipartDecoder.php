<?php

namespace App\Encoder;

use App\Service\GestionDocument;
use App\Service\GestionLicence;
use App\Service\GestionMedia;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';
    public const IMAGE_EXTENSION = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];

    public function __construct(private readonly RequestStack $requestStack, private readonly GestionMedia $gestionMedia, private readonly GestionDocument $gestionDocument, private readonly GestionLicence $gestionLicence)
    {
    }

    public function decode(string $data, string $format, array $context = []): mixed
    {

        $request = $this->requestStack->getCurrentRequest(); //dd($request);

        if (!$request) {
            return null;
        }

        $media = $licence = $casier = null;

        $mediaFile = $request->files->get('media'); //dd($mediaFile);
        if ($mediaFile) {
            // Si le fichier téléchargé est une image alors traiter le
            $mimeType = $mediaFile->getMimeType(); //dd($mimeType);
            if (in_array($mimeType, self::IMAGE_EXTENSION, true)){
                match ($context['groups'][0]){
                    "demandeur.write" => $media = $this->gestionMedia->upload($mediaFile, 'demandeur'),
                    "prestataire.write" => $media = $this->gestionMedia->upload($mediaFile, 'prestataire'),
                    default => $media = $this->gestionMedia->upload($mediaFile, 'upload')
                };
            } else {
                throw new \Exception(" Le fichier téléchargé doit être une image (png, jpeg, jpg, webp");
            }


        }

        if (in_array('prestataire.write', $context['groups'], true)){
            // Gestion des casiers
            $casierFile = $request->files->get('casier');
            if ($casierFile){
                $casier = $this->gestionDocument->upload($casierFile, 'prestataire');
            }

            // Gestion des licences
            $licenceFile = $request->files->get('licence');
            if ($licenceFile){
                $licence = $this->gestionLicence->upload($licenceFile, 'prestataire');
            }
        }

        return  array_map(static function (string $element) {
                // Multipart form values will be encoded in JSON.
                $decoded = json_decode($element, true);

                return \is_array($decoded) ? $decoded : $element;
                },
                $request->request->all()) + [
                    'media' => $media ?: '',
                    'casier' => $casier ?: '',
                    'licence' => $licence ?: ''
            ];

    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}