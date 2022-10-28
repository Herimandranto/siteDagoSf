<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateImage
{


    /**
     * @Assert\File(
     *     maxSize = "5M",
     *     maxSizeMessage = "Taille de l'image ne doit pas depasser 2Mo",
     *     mimeTypes = {"image/jpeg","image/gif","image/png", "image/jpg", "image/svg",},
     *     mimeTypesMessage = "Veuillez Uploader le bon format tell que jpeg, jpg, png"
     * )
     * @var File
     */
    private $imageFile;

 
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

}
