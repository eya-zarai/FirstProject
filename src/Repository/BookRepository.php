<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Recherche des livres par titre (anciennement ref)
     */
    public function searchBookByRef(string $ref): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.title LIKE :ref')
            ->setParameter('ref', '%' . $ref . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Liste des livres triés par auteur
     */
    public function booksListByAuthors(): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->orderBy('a.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Livres publiés avant 2023 dont l'auteur a plus de 10 livres
     */
public function booksBefore2023WithAuthorHavingMoreThan10Books(): array
{
    return $this->createQueryBuilder('b')
        ->innerJoin('b.author', 'a')
        ->andWhere('b.publicationDate < :date')
        ->andWhere('a.nbBooks > :nb')
        ->setParameter('date', new \DateTime('2023-01-01'))
        ->setParameter('nb', 10)
        ->getQuery()
        ->getResult();
}


    /**
     * Met à jour les livres de catégorie "Science-Fiction" en "Romance"
     */
    public function updateSciFiToRomance(): int
    {
        return $this->createQueryBuilder('b')
            ->update()
            ->set('b.category', ':newCategory')
            ->where('b.category = :oldCategory')
            ->setParameter('newCategory', 'Romance')
            ->setParameter('oldCategory', 'Science-Fiction')
            ->getQuery()
            ->execute();
    }
}
