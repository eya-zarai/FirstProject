<?php

namespace App\Controller;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;


final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/{id}', name: 'app_author_details', )]
    public function authorDetails(int $id): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => 'assets/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => 'assets/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => 'assets/images/Taha-Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ]
        ];

        $author = null;
        foreach ($authors as $a) {
            if ($a['id'] === $id) {
                $author = $a;
                break;
            }
        }

        if (!$author) {
            throw $this->createNotFoundException("Auteur non trouvé !");
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author
        ]);
    }

    #[Route('/authorslist', name: 'listAuthors')]
    public function listAuthors(): Response
    {
        $authors = [
            [
                'id' => 1,
                'picture' => 'assets/images/Victor-Hugo.jpg',
                'username' => 'Victor Hugo',
                'email' => 'victor.hugo@gmail.com',
                'nb_books' => 100
            ],
            [
                'id' => 2,
                'picture' => 'assets/images/william-shakespeare.jpg',
                'username' => 'William Shakespeare',
                'email' => 'william.shakespeare@gmail.com',
                'nb_books' => 200
            ],
            [
                'id' => 3,
                'picture' => 'assets/images/Taha-Hussein.jpg',
                'username' => 'Taha Hussein',
                'email' => 'taha.hussein@gmail.com',
                'nb_books' => 300
            ]
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors
        ]);
    }


    #[Route('/addStatic', name: 'add_static_author')]
    public function addStaticAuthor(ManagerRegistry $doctrine ): Response
{
    $em=$doctrine->getManager();
    $author = new Author();
    $author->setUsername("eya_zarai");
    $author->setEmail("eya@example.com");
    $author->setAge(23);

    $em->persist($author);
    $em->flush();

    return new Response("Auteur ajouté avec succès : ".$author->getUsername());
}


#[Route('/ShowAllAuthor', name: 'ShowAllAuthor')]
public function ShowAllAuthor(AuthorRepository $repo)
{

    $authors = $repo->listAuthorByEmail();

    return $this->render('author/listAuthor.html.twig', [
        'list' => $authors
    ]);
}





    #[Route(path: '/add', name:'add')]
    public function Add(ManagerRegistry $doctrine){
       $author=new Author();
       $author->setUsername('Test');
       $author->setEmail('test@esprit.tn');
       $author->setAge(25);
       $em=$doctrine->getManager();
       $em->persist($author);
       $em->flush();
       return new Response('Author add successfully');
    }

    

    
    #[Route(path: '/showDetailsAuthor/{id}', name:'showDetailsAuthor')]

    public function showDetailsAuthor(AuthorRepository $repo ,$id){
        $author=$repo->find($id);
        return $this->render('author/showDetailsAuthor.html.twig',[$author=>Author]);
    }


    



    #[Route('/addAuthorForm', name: 'addAuthorForm')]
    public function addAuthorForm(ManagerRegistry $doctrine, Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('ShowAllAuthor');
        }

        return $this->render('author/addAuthor.html.twig', [
            'formA' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}',name:'delete')]
    public function Delete( ManagerRegistry $doctrine, $id, AuthorRepository $repo){
           $author=$repo->find($id);
           if (!$author) {
                throw $this->createNotFoundException("Author not found.");
            }
           $em=$doctrine->getManager();
           $em->remove($author);
           $em->flush();
           return $this->redirectToRoute('ShowAllAuthor');
        }


    #[Route('/edit/{id}', name: 'editAuthor')]
    public function editAuthor(ManagerRegistry $doctrine, Request $request, AuthorRepository $repo, $id)
    {
        $author = $repo->find($id);
        if (!$author) {
            throw $this->createNotFoundException("Author not found!");
        }

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('ShowAllAuthor');
        }

        return $this->render('author/editAuthor.html.twig', [
            'formA' => $form->createView()
        ]);
    }



    #[Route('/authors/email', name: 'authors_by_email')]
public function authorsByEmail(AuthorRepository $repo)
{
    $authors = $repo->listAuthorByEmail();
    return $this->render('author/listAuthor.html.twig', [
        'list' => $authors,
    ]);
}


}
