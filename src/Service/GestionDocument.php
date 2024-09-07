<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GestionDocument
{
    private $mediaDocument;

    public function __construct(
        $documentDirectory
    )
    {
        $this->mediaDocument = $documentDirectory;
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
        $mediaFile = $form->get('casier')->getData();
        if ($mediaFile){
            $media = $this->upload($mediaFile, $entityName); //dd($entity->getDocument());

//            if ($entity->getCasier()){
//                $this->removeUpload($entity->getCasier(), $entityName);
//            }

            $entity->setCasier($media);
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
            if ($media === 'prestataire') $file->move($this->mediaDocument, $newFilename);
            else $file->move($this->mediaDocument, $newFilename);
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
        if ($media === 'prestataire') unlink($this->mediaDocument.'/'.$ancienMedia);
        else return false;

        return true;
    }

}