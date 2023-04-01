<?php
declare(strict_types=1);

namespace App\Character\Entity;

use App\Actor\Entity\Actor;
use App\Character\Repository\CharacterRepository;
use App\Movie\Entity\MovieCharacter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\UniqueConstraint(name: 'UQ_Character_Signature', columns: ['role_name', 'actor_id'])]
class Character
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $roleName = null;

    #[ORM\ManyToOne(inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Actor $actor = null;

    #[ORM\OneToMany(mappedBy: 'character', targetEntity: MovieCharacter::class, cascade: ['persist'], orphanRemoval: true)]
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

    public function getMovies(): array
    {
        // TODO clear this using array functions ! ! !
        $movies = [];

        foreach ($this->getMovieCharacters() as $movieCharacter)
        {
            $movies[] = $movieCharacter;
        }

        return array_unique($movies);
    }

    public function __toString(): string
    {
        return sprintf('%s', $this->getRoleName());
    }
}
