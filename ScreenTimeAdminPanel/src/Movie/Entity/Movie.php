<?php

declare(strict_types=1);

namespace App\Movie\Entity;

use App\Movie\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\UniqueConstraint(name: 'UQ_Movie_Signature', columns: ['title', 'production_year'])]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $productionYear = null;

    #[ORM\Column()]
    private ?int $durationTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $worldPremiereDate = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieGenre::class)]
    private Collection $movieGenre;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: MovieCharacter::class, cascade: ['persist'])]
    private Collection $movieCharacters;

    public function __construct()
    {
        $this->movieGenre = new ArrayCollection();
        $this->movieCharacters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getProductionYear(): ?\DateTimeInterface
    {
        return $this->productionYear;
    }

    public function setProductionYear(\DateTimeInterface $productionYear): self
    {
        $this->productionYear = $productionYear;

        return $this;
    }

    public function getDurationTime(): ?int
    {
        return $this->durationTime;
    }

    public function setDurationTime(int $durationTime): self
    {
        $this->durationTime = $durationTime;

        return $this;
    }

    public function getWorldPremiereDate(): ?\DateTimeInterface
    {
        return $this->worldPremiereDate;
    }

    public function setWorldPremiereDate(\DateTimeInterface $worldPremiereDate): self
    {
        $this->worldPremiereDate = $worldPremiereDate;

        return $this;
    }

    /**
     * @return Collection<int, MovieGenre>
     */
    public function getGenre(): Collection
    {
        return $this->movieGenre;
    }

    public function addGenre(MovieGenre $movieGenre): self
    {
        if (!$this->movieGenre->contains($movieGenre)) {
            $this->movieGenre->add($movieGenre);
            $movieGenre->setMovie($this);
        }

        return $this;
    }

    public function removeGenre(MovieGenre $movieGenre): self
    {
        if ($this->movieGenre->removeElement($movieGenre)) {
            // set the owning side to null (unless already changed)
            if ($movieGenre->getMovie() === $this) {
                $movieGenre->setMovie(null);
            }
        }

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
            $movieCharacter->setMovie($this);
        }

        return $this;
    }

    public function removeMovieCharacter(MovieCharacter $movieCharacter): self
    {
        if ($this->movieCharacters->removeElement($movieCharacter)) {
            // set the owning side to null (unless already changed)
            if ($movieCharacter->getMovie() === $this) {
                $movieCharacter->setMovie(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s', $this->getTitle());
    }
}
