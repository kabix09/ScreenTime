<?php

namespace App\Character\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Actor\Entity\Actor;
use App\Character\Repository\CharacterRepository;
use App\Movie\Entity\MovieCharacter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\UniqueConstraint(name: 'UQ_Character_Signature', columns: ['role_name', 'actor_id'])]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'character:item']),
        new GetCollection(normalizationContext: ['groups' => 'character:list'])
    ],
    paginationEnabled: false,
)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['character:list', 'character:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    #[Groups(['character:list', 'character:item'])]
    private ?string $roleName = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['character:list', 'character:item'])]
    private ?Actor $actor = null;

    #[ORM\OneToMany(mappedBy: 'character', targetEntity: MovieCharacter::class)]
    #[Groups(['character:list', 'character:item'])]
    private Collection $movieCharacters;

    public function __construct()
    {
        $this->movieCharacters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): self
    {
        $this->roleName = $roleName;

        return $this;
    }

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): self
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * @return Collection<int, MovieCharacter>
     */
    public function getMovieCharacters(): Collection
    {
        return $this->movieCharacters;
    }

    public function addMovieCharacter(MovieCharacter $movieCharacter): self
    {
        if (!$this->movieCharacters->contains($movieCharacter)) {
            $this->movieCharacters->add($movieCharacter);
            $movieCharacter->setCharacter($this);
        }

        return $this;
    }

    public function removeMovieCharacter(MovieCharacter $movieCharacter): self
    {
        if ($this->movieCharacters->removeElement($movieCharacter)) {
            // set the owning side to null (unless already changed)
            if ($movieCharacter->getCharacter() === $this) {
                $movieCharacter->setCharacter(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s', $this->getRoleName());
    }
}
