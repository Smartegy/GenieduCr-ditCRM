<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/email', name: 'email')]
    public function index(): Response
    {
        dd('hello world') ; die () ;
        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }




}
