<?php

declare(strict_types=1);

namespace App\Genre\Entity;

use App\Genre\Repository\GenreRepository;
use App\Movie\Entity\MovieGenre;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\UniqueConstraint(name: 'UQ_Character_Signature', columns: ['name'])]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'genre', targetEntity: MovieGenre::class)]
    private Collection $movieGenres;

    public function __construct()
    {
        $this->movieGenres = new ArrayCollection();
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

    /**
     * @return Collection<int, MovieGenre>
     */
    public function getMovieGenres(): Collection
    {
        return $this->movieGenres;
    }

    public function addMovieGenre(MovieGenre $movieGenre): self
    {
        if (!$this->movieGenres->contains($movieGenre)) {
            $this->movieGenres->add($movieGenre);
            $movieGenre->setGenre($this);
        }

        return $this;
    }

    public function removeMovieGenre(MovieGenre $movieGenre): self
    {
        if ($this->movieGenres->removeElement($movieGenre)) {
            // set the owning side to null (unless already changed)
            if ($movieGenre->getGenre() === $this) {
                $movieGenre->setGenre(null);
            }
        }

        return $this;
    }
}
