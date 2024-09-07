<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GestionLicence
{
    private $mediaLicence;

    public function __construct(
        $licenceDirectory,
    )
    {
        $this->mediaLicence = $licenceDirectory;
    }

    /**
     * @param $form
     * @param object $entity
     * @param string $entityName
     * @return void
     */
    public function media($form, object $entity, string $entityName): void
    {
        // Gestion des médias
        $mediaFile = $form->get('licence')->getData();
        if ($mediaFile){
            $media = $this->upload($mediaFile, $entityName);

//            if ($entity->getLicence()){
//                $this->removeUpload($entity->getLicence(), $entityName);
//            }

            $entity->setLicence($media);
        }
    }


    /**
     * @param UploadedFile $file
     * @param $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null): string
    {
        // Initialisation du slug
        $slugify = new AsciiSlugger();

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slug($originalFileName);
        $newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension();

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'prestataire') $file->move($this->mediaLicence, $newFilename);
            else $file->move($this->mediaLicence, $newFilename);
        }catch (FileException $e){

        }

        return $newFilename;
    }

    /**
     * Suppression de l'ancien media sur le server
     *
     * @param $ancienMedia
     * @param null $media
     * @return bool
     */
    public function removeUpload($ancienMedia, $media = null): bool
    {
        if ($media === 'prestataire') unlink($this->mediaLicence.'/'.$ancienMedia);
        else return false;

        return true;
    }

}