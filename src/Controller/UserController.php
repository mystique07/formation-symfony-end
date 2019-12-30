<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     * @ParamConverter("user" ,class="App\Entity\User")
     * @param User $user
     * @return Response
     */
    public function index(User $user)
    {

        //@ParamConverter("post", class="SensioBlogBundle:Post")
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
