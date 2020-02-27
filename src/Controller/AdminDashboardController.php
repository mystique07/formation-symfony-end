<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     * @param EntityManagerInterface $manager
     * @param StatsService $statsService
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index( EntityManagerInterface $manager, StatsService $statsService): Response
    {

        $stats = $statsService->getStats();

        $bestAds = $statsService->getAdsStats('DESC');

        $worstAds =$statsService->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats'=> $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
