<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    
    /*private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }*/

    /**
     * @Route("/", name="home")
     * @param AdRepository $repository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function home(AdRepository $repository, UserRepository $userRepository): Response
    {
       // $this->environment->render('home.html.twig');
        return  $this->render('home.html.twig',[
            'ads' => $repository->findBestAds(3),
            'users' => $userRepository->findBestUsers(2)
        ]);
    }
}