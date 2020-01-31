<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page}", name="admin_ad" , requirements={"page": "\d+"})
     * @param AdRepository $repository
     * @param int $page
     * @param PaginationService $paginationService
     * @return Response
     */
    public function index(AdRepository $repository, $page =1, PaginationService $paginationService): Response
    {
        //Methode find permet de retrouver un enregistrement par son numero
        //$ad = $repository->find(27);
        /*$ad=$repository->findOneBy([
            'title'=> 'Officiis et facere quia et.',
        ]);*/

        //$ad = $repository->findBy([],[],5,5);

        $paginationService->setEntityClass(Ad::class)
                        ->setPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' =>$paginationService
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée ! "
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad'=> $ad,
            'form' =>$form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce sans reservation
     *
     *  @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * @param Ad $ad
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Ad $ad, EntityManagerInterface $manager): RedirectResponse
    {
        if (count($ad->getBookings()) >0){

            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce  <strong> {$ad->getTitle()} </strong> car elle possède déjà des réservations !"
            );
        }else{
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong> {$ad->getTitle()} </strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ad');

    }
}
