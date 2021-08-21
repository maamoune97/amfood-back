<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectoryPath;
    private $targetDirectoryName;
    private $slugger;

    public function __construct($targetDirectoryPath, $targetDirectoryName, SluggerInterface $slugger)
    {
        $this->targetDirectoryPath = $targetDirectoryPath;
        $this->targetDirectoryName = $targetDirectoryName;
        $this->slugger = $slugger;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file fichier à upluoder
     * @param string $pathDirectory chemin du dossier sans les slash du debut et fin
     * @return string $fileName
     */
    public function upload(UploadedFile $file, string $pathDirectory)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try
        {
            $file->move($this->getTargetDirectoryPath().$pathDirectory.'/', $fileName);
        }
        catch (FileException $e)
        {
            throw $e;
            // ... handle exception if something happens during file upload
        }

        //TODO return $filePath and change template src form img balises
        $filePath = $this->getServerHost().'/'.$this->targetDirectoryName.'/'.$pathDirectory.'/'.$fileName;

        return $fileName;
    }

    public function getTargetDirectoryPath()
    {
        return $this->targetDirectoryPath;
    }

    public function getServerHost(): string
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";

        // Here append the common URL characters.
        $link .= "://";
        
        // Append the host(domain name, ip) to the URL.
        $link .= $_SERVER['HTTP_HOST'];

        return $link;
    }
}
