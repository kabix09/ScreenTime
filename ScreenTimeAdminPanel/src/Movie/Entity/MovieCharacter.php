<?php

declare(strict_types=1);

namespace App\Movie\Entity;

use App\Character\Entity\Character;
use App\Movie\Repository\MovieCharacterRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: MovieCharacterRepository::class)]
class MovieCharacter
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'movieCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    //#[ORM\Id] // EasyAdmin dont support composed keys
    #[ORM\ManyToOne(inversedBy: 'movieCharacters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Character $character = null;

    #[ORM\Column]
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
