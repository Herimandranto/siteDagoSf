<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: ClientRepository::class)]
/**
 * @ORM\Entity 
 * @Vich\Uploadable
 */
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;


    /**
     * @Assert\File(
     *     maxSize = "5M",
     *     maxSizeMessage = "Taille de l'image ne doit pas depasser 2Mo",
     *     mimeTypes = {"image/jpeg","image/gif","image/png", "image/jpg", "image/svg",},
     *     mimeTypesMessage = "Veuillez Uploader le bon format tell que jpeg, jpg, png"
     * )
     * @Vich\UploadableField(mapping="upload_avatar", fileNameProperty="avatar")
     * 
     * @var File
     */
    private $avatarFile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setAvatarFile(File $avatar = null)
    {
        $this->avatarFile = $avatar;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($avatar) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getAvatarFile()
    {
        return $this->avatarFile;
    }


    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar = null): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
