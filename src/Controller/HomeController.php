<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController {

    /*private $environment;
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }*/

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
        //$this->environment->render('home.html.twig');
        return  $this->render('home.html.twig');
    }
}