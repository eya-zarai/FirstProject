<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WelcomeController extends AbstractController
{
    #[Route('/welcome', name: 'app_welcome')] 
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig', [
            'controller_name' => 'WelcomeController',
            'classe'=> '3A24',
        ]);
    }
    #[Route('/hello/{id}','hello')]
    public function show($id):Response{
        //return new Response("hello 3A24".$id);
        return $this->render('welcome/index.html.twig',['identifiant'=>$id]);
    }
}
