<?php

declare(strict_types=1);

namespace App\Country\Entity;

use App\Actor\Entity\Actor;
use App\Country\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $iso = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 80)]
    private ?string $nicename = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $iso3 = null;

    #[ORM\Column(nullable: true)]
    private ?int $numcode = null;

    #[ORM\Column]
    private ?int $phonecode = null;

    #[ORM\OneToMany(mappedBy: 'nationality', targetEntity: Actor::class)]
    private Collection $actors;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(string $iso): self
    {
        $this->iso = $iso;

        return $this;
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

    public function getNicename(): ?string
    {
        return $this->nicename;
    }

    public function setNicename(string $nicename): self
    {
        $this->nicename = $nicename;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getNumcode(): ?int
    {
        return $this->numcode;
    }

    public function setNumcode(?int $numcode): self
    {
        $this->numcode = $numcode;

        return $this;
    }

    public function getPhonecode(): ?int
    {
        return $this->phonecode;
    }

    public function setPhonecode(int $phonecode): self
    {
        $this->phonecode = $phonecode;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->setNationality($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            // set the owning side to null (unless already changed)
            if ($actor->getNationality() === $this) {
                $actor->setNationality(null);
            }
        }

        return $this;
    }
}
