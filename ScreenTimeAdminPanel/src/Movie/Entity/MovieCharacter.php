<?php

declare(strict_types=1);

namespace App\Movie\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Character\Entity\Character;
use App\Movie\Repository\MovieCharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: MovieCharacterRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'movie_character:item']),
        new GetCollection(normalizationContext: ['groups' => 'movie_character:list'])
    ],
    paginationEnabled: false,
)]
class MovieCharacter
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'movieCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movie_character:list', 'movie:item'])]
    private ?Movie $movie = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'movieCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['movie_character:list', 'movie:item'])]
    private ?Character $character = null;

    #[ORM\Column]
    #[Groups(['movie_character:list', 'movie:item'])]
    private ?int $timeOnScene = null;

    public function __construct(?Movie $movie=null, ?Character $character=null, int $timeOnScene = 0)
    {
        $this->setMovie($movie);
        $this->setCharacter($character);

        $this->timeOnScene = $timeOnScene;
    }

    /**
     * @return int
     */
    public function getTimeOnScene(): int
    {
        return $this->timeOnScene;
    }

    /**
     * @param int $timeOnScene
     */
    public function setTimeOnScene(int $timeOnScene): void
    {
        $this->timeOnScene = $timeOnScene;
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

    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    public function setCharacter(?Character $character): self
    {
        $this->character = $character;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getMovie(), $this->getCharacter());
    }
}
