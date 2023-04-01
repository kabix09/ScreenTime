<?php
declare(strict_types=1);

namespace App\Actor\Entity;

use App\Actor\Repository\ActorRepository;
use App\Character\Entity\Character;
use App\Country\Entity\Country;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
class Actor
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 55)]
    private ?string $name = null;

    #[ORM\Column(length: 70)]
    private ?string $surname = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\ManyToOne(inversedBy: 'actors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $nationality = null;

    #[ORM\OneToMany(mappedBy: 'actor', targetEntity: Character::class, cascade: ['persist'])]
    private Collection $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAge(): int
    {
        return (int)(new \DateTime('now'))->diff($this->getBirthDate())->format('%y%');
    }

    public function getNationality(): ?Country
    {
        return $this->nationality;
    }

    public function setNationality($nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->setActor($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getActor() === $this) {
                $character->setActor(null);
            }
        }

        return $this;
    }

    public function getCharactersAmount(): int
    {
        return $this->characters->count();
    }

    public function getMovies(): array
    {
        // TODO clear this using array functions ! ! !
        $movies = [];

        foreach ($this->getCharacters() as $character)
        {
            foreach ($character->getMovieCharacters() as $movieCharacter)
            {
                $movies[] = $movieCharacter;
            }
        }

        return array_unique($movies);
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->getName(), $this->getSurname());
    }
}
