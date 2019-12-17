<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController {

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * 
     *@Route("/", name="home")
     */
    public function home()
    {
        $html =$this->environment->render('home.html.twig',
        [
            'title' => 'eldy salut',
            'age' => '31'
            ]);
        return new Response($html);
    }
}