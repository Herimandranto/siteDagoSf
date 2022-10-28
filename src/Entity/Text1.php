<?php

namespace App\Entity;

use App\Repository\Text1Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Text1Repository::class)]
class Text1
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title1 = null;

    #[ORM\Column(length: 255)]
    private ?string $title2 = null;

    #[ORM\Column(length: 255)]
    private ?string $title3 = null;

    #[ORM\Column(length: 255)]
    private ?string $title4 = null;

    #[ORM\Column(length: 255)]
    private ?string $text1 = null;

    #[ORM\Column(length: 255)]
    private ?string $text2 = null;

    #[ORM\Column(length: 255)]
    private ?string $text3 = null;

    #[ORM\Column(length: 255)]
    private ?string $text4 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle1(): ?string
    {
        return $this->title1;
    }

    public function setTitle1(string $title1): self
    {
        $this->title1 = $title1;

        return $this;
    }

    public function getTitle2(): ?string
    {
        return $this->title2;
    }

    public function setTitle2(string $title2): self
    {
        $this->title2 = $title2;

        return $this;
    }

    public function getTitle3(): ?string
    {
        return $this->title3;
    }

    public function setTitle3(string $title3): self
    {
        $this->title3 = $title3;

        return $this;
    }

    public function getTitle4(): ?string
    {
        return $this->title4;
    }

    public function setTitle4(string $title4): self
    {
        $this->title4 = $title4;

        return $this;
    }

    public function getText1(): ?string
    {
        return $this->text1;
    }

    public function setText1(string $text1): self
    {
        $this->text1 = $text1;

        return $this;
    }

    public function getText2(): ?string
    {
        return $this->text2;
    }

    public function setText2(string $text2): self
    {
        $this->text2 = $text2;

        return $this;
    }

    public function getText3(): ?string
    {
        return $this->text3;
    }

    public function setText3(string $text3): self
    {
        $this->text3 = $text3;

        return $this;
    }

    public function getText4(): ?string
    {
        return $this->text4;
    }

    public function setText4(string $text4): self
    {
        $this->text4 = $text4;

        return $this;
    }
}
