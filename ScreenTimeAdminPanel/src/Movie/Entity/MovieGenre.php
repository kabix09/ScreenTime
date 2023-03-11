<?php

declare(strict_types=1);

namespace App\Movie\Entity;

use App\Genre\Entity\Genre;
use App\Movie\Repository\MovieGenreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieGenreRepository::class)]
class MovieGenre
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'movieGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'movieGenres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    public function __construct(Movie $movie, Genre $genre)
    {
        $this->setMovie($movie);
        $this->setGenre($genre);
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }
}
