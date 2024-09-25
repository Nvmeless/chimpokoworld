<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $realName = null;

    #[ORM\Column(length: 255)]
    private ?string $realPath = null;

    #[ORM\Column(length: 255)]
    private ?string $publicPath = null;

    #[ORM\Column(length: 72)]
    private ?string $mimeType = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;
    
    #[ORM\Column(length: 25)]
    private ?string $status = null;


    #[Vich\UploadableField('medias', fileNameProperty:"realPath",mimeType:"mimeType", originalName:"realName")]
    private $file;
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): Media
    {
        $this->file = $file;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRealName(): ?string
    {
        return $this->realName;
    }

    public function setRealName(string $realName): static
    {
        $this->realName = $realName;

        return $this;
    }

    public function getRealPath(): ?string
    {
        return $this->realPath;
    }

    public function setRealPath(string $realPath): static
    {
        $this->realPath = $realPath;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return $this->publicPath;
    }

    public function setPublicPath(string $publicPath): static
    {
        $this->publicPath = $publicPath;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }
}
