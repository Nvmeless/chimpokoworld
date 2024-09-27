<?php

namespace App\Entity;

use App\Repository\ChimpokodexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ChimpokodexRepository::class)]
class Chimpokodex
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chimpokomon', "chimpokodex"])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['chimpokomon', "chimpokodex"])]
    #[Assert\Length(min: 3, max: 255, minMessage: "Le nom du Chimpokomon doit faire au moin {{limit}}")]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(['chimpokomon', "chimpokodex"])]

    private ?int $pvMin = null;

    #[ORM\Column]
    #[Groups(['chimpokomon', "chimpokodex"])]

    private ?int $pvMax = null;

    #[ORM\Column]
    #[Groups(['chimpokomon', "chimpokodex"])]

    private ?int $idDad = null;

    #[ORM\Column]
    #[Groups(['chimpokomon', "chimpokodex"])]

    private ?int $idMom = null;

    /**
     * @var Collection<int, Chimpokomon>
     */
    #[ORM\OneToMany(targetEntity: Chimpokomon::class, mappedBy: 'chimpokodex')]
    #[Groups(["chimpokodex"])]

    private Collection $chimpokomons;

    public function __construct()
    {
        $this->chimpokomons = new ArrayCollection();
    }
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

    public function getPvMin(): ?int
    {
        return $this->pvMin;
    }

    public function setPvMin(int $pvMin): static
    {
        $this->pvMin = $pvMin;

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

    public function getIdDad(): ?int
    {
        return $this->idDad;
    }

    public function setIdDad(int $idDad): static
    {
        $this->idDad = $idDad;

        return $this;
    }

    public function getIdMom(): ?int
    {
        return $this->idMom;
    }

    public function setIdMom(int $idMom): static
    {
        $this->idMom = $idMom;

        return $this;
    }

    /**
     * @return Collection<int, Chimpokomon>
     */
    public function getChimpokomons(): Collection
    {
        return $this->chimpokomons;
    }

    public function addChimpokomon(Chimpokomon $chimpokomon): static
    {
        if (!$this->chimpokomons->contains($chimpokomon)) {
            $this->chimpokomons->add($chimpokomon);
            $chimpokomon->setChimpokodex($this);
        }

        return $this;
    }

    public function removeChimpokomon(Chimpokomon $chimpokomon): static
    {
        if ($this->chimpokomons->removeElement($chimpokomon)) {
            // set the owning side to null (unless already changed)
            if ($chimpokomon->getChimpokodex() === $this) {
                $chimpokomon->setChimpokodex(null);
            }
        }

        return $this;
    }
}
