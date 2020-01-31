<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_user_index")
     * @param UserRepository $repository
     * @return Response
     */
    public function index(UserRepository $repository, $page, PaginationService $paginationService): Response
    {
        $paginationService->setEntityClass(User::class)
            ->setLimit(5)
            ->setPage($page);

        return $this->render('admin/user/index.html.twig', [
            'pagination' =>$paginationService ,
        ]);
    }
}
