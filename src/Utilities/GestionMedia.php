<?php


namespace App\Utilities;


use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GestionMedia
{
    private $mediaPresentation;
    private $mediaSoutien;
    private $mediaAdulte;
    private $mediaCover;
	private $mediaImage;
	private $mediaRecrutement;
	
	public function __construct($presentationDirectory, $soutienDirectory, $adulteDirectory, $coverDirectory,
        $mediaDirectory, $recrutementDirectory
    )
    {
        $this->mediaPresentation = $presentationDirectory;
        $this->mediaSoutien = $soutienDirectory;
        $this->mediaAdulte = $adulteDirectory;
        $this->mediaCover = $coverDirectory;
	    $this->mediaImage = $mediaDirectory;
	    $this->mediaRecrutement = $recrutementDirectory;
    }

    /**
     * Enregistrement du fichier dans le repertoire approprié
     *
     * @param UploadedFile $file
     * @param null $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null)
    {
        // Initialisation du slug
        $slugify = new Slugify(); //dd($file);

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slugify($originalFileName);
        $newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension(); //dd($this->mediaActivite);

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'presentation') $file->move($this->mediaPresentation, $newFilename);
            elseif ($media === 'soutien') $file->move($this->mediaSoutien, $newFilename);
            elseif ($media === 'adulte') $file->move($this->mediaAdulte, $newFilename);
            elseif ($media === 'cover') $file->move($this->mediaCover, $newFilename);
            elseif ($media === 'media') $file->move($this->mediaImage, $newFilename);
            elseif ($media === 'recrutement') $file->move($this->mediaRecrutement, $newFilename);
            else $file->move($this->mediaPresentation, $newFilename);
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
    public function removeUpload($ancienMedia, $media = null)
    {
        if ($media === 'presentation') unlink($this->mediaPresentation.'/'.$ancienMedia);
        elseif ($media === 'soutien') unlink($this->mediaSoutien.'/'.$ancienMedia);
        elseif ($media === 'adulte') unlink($this->mediaAdulte.'/'.$ancienMedia);
        elseif ($media === 'cover') unlink($this->mediaCover.'/'.$ancienMedia);
        elseif ($media === 'media') unlink($this->mediaImage.'/'.$ancienMedia);
        elseif ($media === 'recrutement') unlink($this->mediaRecrutement.'/'.$ancienMedia);
        else return false;

        return true;
    }
}
