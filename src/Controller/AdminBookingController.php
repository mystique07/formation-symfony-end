<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     * @param BookingRepository $repository
     * @param $page
     * @param PaginationService $paginationService
     * @return Response
     */
    public function index(BookingRepository $repository, $page, PaginationService $paginationService): Response
    {
         $paginationService->setEntityClass(Booking::class)
             ->setPage($page);
        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $paginationService,
        ]);
    }

    /**
     * Permet d'editer une reservation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Booking $booking,Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée "
            );
            return  $this->redirectToRoute('admin_booking_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking'=>$booking
        ]);

    }

    /**
     * Permet de supprimer une réservations
     *
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     * @param Booking $booking
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function delete(Booking $booking, EntityManagerInterface $manager):RedirectResponse
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            'La réservation a bien été supprimé'
        );

        return $this->redirectToRoute('admin_booking_index');
    }


}
