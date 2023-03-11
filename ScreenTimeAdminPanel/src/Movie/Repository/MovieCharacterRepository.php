<?php

declare(strict_types=1);

namespace App\Movie\Repository;

use App\Movie\Entity\MovieCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MovieCharacter>
 *
 * @method MovieCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieCharacter[]    findAll()
 * @method MovieCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieCharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovieCharacter::class);
    }

    public function save(MovieCharacterRepository $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MovieCharacterRepository $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}