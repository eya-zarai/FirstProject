<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book/add', name: 'book_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em->persist($book);
            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès !');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books', name: 'book_list')]
    public function list(BookRepository $repo): Response
    {
        $books = $repo->findAll();
        return $this->render('book/listBook.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/book/edit/{id}', name: 'book_edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Livre modifié avec succès !');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/book/delete/{id}', name: 'book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $author = $book->getAuthor();
        $em->remove($book);
        $author->setNbBooks(max(0, $author->getNbBooks() - 1));

        if ($author->getNbBooks() === 0) {
            $em->remove($author);
        }

        $em->flush();

        $this->addFlash('success', 'Livre supprimé avec succès !');
        return $this->redirectToRoute('book_list');
    }

    #[Route('/book/show/{id}', name: 'book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/showbooks.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/books/search', name: 'search_book')]
    public function searchBook(Request $request, BookRepository $repo): Response
    {
        $ref = $request->query->get('ref', '');
        $books = $ref ? $repo->searchBookByRef($ref) : $repo->findAll();

        return $this->render('book/listBook.html.twig', [
            'books' => $books,
        ]);
    }

#[Route('/books/before2023', name: 'books_before_2023')]
public function booksBefore2023(BookRepository $repo): Response
{
    $oldBooks = $repo->booksBefore2023WithAuthorHavingMoreThan10Books();

    return $this->render('book/listBook.html.twig', [
        'oldBooks' => $oldBooks
    ]);
}






}
