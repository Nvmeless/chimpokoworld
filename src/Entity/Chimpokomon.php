<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ChimpokomonRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChimpokomonRepository::class)]
class Chimpokomon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chimpokomon'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['chimpokomon'])]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    
    private ?string $status = null;
    #[ORM\Column]
    #[Groups(['chimpokomon'])]
    private ?int $pv = null;

    #[ORM\Column]
    #[Groups(['chimpokomon'])]
    private ?int $pvMax = null;

    #[ORM\ManyToOne(inversedBy: 'chimpokomons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['chimpokomon'])]
    private ?Chimpokodex $chimpokodex = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getChimpokodex(): ?Chimpokodex
    {
        return $this->chimpokodex;
    }

    public function setChimpokodex(?Chimpokodex $chimpokodex): static
    {
        $this->chimpokodex = $chimpokodex;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

        return $this;
    }

    public function getPvMax(): ?int
    {
        return $this->pvMax;
    }

    public function setPvMax(int $pvMax): static
    {
        $this->pvMax = $pvMax;

        return $this;
    }

}
