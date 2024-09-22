<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GestionMedia
{
    private $mediaDemandeur;
    private $mediaPrestataire;
    private $mediaSlide;
    private $mediaPartenaire;
    private $mediaAction;
    private $mediaParallax;
    private $mediaProjet;

    public function __construct(
        $demandeurDirectory, $prestataireDirectory, $slideDirectory, $partenaireDirectory,
        $actionDirectory, $parallaxDirectory, $projetDirectory
    )
    {
        $this->mediaDemandeur = $demandeurDirectory;
        $this->mediaPrestataire = $prestataireDirectory;
        $this->mediaSlide = $slideDirectory;
        $this->mediaPartenaire = $partenaireDirectory;
        $this->mediaAction = $actionDirectory;
        $this->mediaParallax = $parallaxDirectory;
        $this->mediaProjet = $projetDirectory;
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
        $mediaFile = $form->get('media')->getData();
        if ($mediaFile){
            $media = $this->upload($mediaFile, $entityName);

//            if ($entity->getMedia()){
//                $this->removeUpload($entity->getMedia(), $entityName);
//            }

            $entity->setMedia($media);
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
            if ($media === 'demandeur') $file->move($this->mediaDemandeur, $newFilename);
            elseif ($media === 'prestataire') $file->move($this->mediaPrestataire, $newFilename);
            elseif ($media === 'slide') $file->move($this->mediaSlide, $newFilename);
            elseif ($media === 'partenaire') $file->move($this->mediaPartenaire, $newFilename);
            elseif ($media === 'action') $file->move($this->mediaAction, $newFilename);
            elseif ($media === 'parallax') $file->move($this->mediaParallax, $newFilename);
            elseif ($media === 'projet') $file->move($this->mediaProjet, $newFilename);
            else $file->move($this->mediaDemandeur, $newFilename);
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
        if ($media === 'demandeur') unlink($this->mediaDemandeur.'/'.$ancienMedia);
        elseif ($media === 'prestataire') unlink($this->mediaPrestataire.'/'.$ancienMedia);
        elseif ($media === 'slide') unlink($this->mediaSlide.'/'.$ancienMedia);
        elseif ($media === 'partenaire') unlink($this->mediaPartenaire.'/'.$ancienMedia);
        elseif ($media === 'action') unlink($this->mediaAction.'/'.$ancienMedia);
        elseif ($media === 'parallax') unlink($this->mediaParallax.'/'.$ancienMedia);
        elseif ($media === 'projet') unlink($this->mediaProjet.'/'.$ancienMedia);
        else return false;

        return true;
    }

}