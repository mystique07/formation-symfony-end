<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @param Ad $ad
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function book(Ad $ad, Request $request, EntityManagerInterface $entityManager)
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user =$this->getUser();
            $booking->setBooker($user)
                ->setAd($ad);
            //Si les dates ne sont pas disponibles, message d'erreur
            if (!$booking->isBookableDates()){
                    $this->addFlash(
                        'warning',
                        "Les dates que vous avez choisi ne peuvent être réservée : elles sont déjà prises ."
                    );
            }else {

                //Sinon enregistrement et redirection
                $entityManager->persist($booking);
                $entityManager->flush();

                return $this->redirectToRoute('booking_show', ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad'=>$ad,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/booking/{id}" , name="booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' =>$booking
        ]);
    }
}
