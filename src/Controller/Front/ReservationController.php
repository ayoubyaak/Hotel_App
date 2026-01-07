<?php


namespace App\Controller\Front;

use App\Entity\Client;
use App\Entity\Payment;
use App\Entity\Reservation;
use App\Form\FrontReservationType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/reservation', name: 'front_reservation_')]
class ReservationController extends AbstractController
{
    #[Route('/book/{roomId}', name: 'book')]
    public function book(
        int $roomId,
        Request $request,
        RoomRepository $roomRepo,
        ReservationRepository $resRepo,
        EntityManagerInterface $em
    ): Response {
        $room = $roomRepo->find($roomId);
        if (!$room) {
            throw $this->createNotFoundException('Room not found');
        }

        $reservation = new Reservation();
        $form = $this->createForm(FrontReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Dates
            $start = $reservation->getStartDate();
            $end   = $reservation->getEndDate();

            // (اختياري) availability
            // if (!$resRepo->isRoomAvailable(...)) { }

            // Client
            $client = new Client();
            $client->setFullName($form->get('guestFullName')->getData());
            $client->setEmail($form->get('guestEmail')->getData());
            $client->setPhone($form->get('guestPhone')->getData());
            $client->setCin($form->get('guestCin')->getData() ?? '');
            $em->persist($client);

            // Reservation
            $reservation->setClient($client);
            $reservation->setRoom($room);

            $days = max(1, $start->diff($end)->days);
            $reservation->setTotalPrice($days * $room->getPrice());

            $em->persist($reservation);
            $em->flush(); // لازمها قبل Payment

            // ================= PAYMENT =================
            $payment = new Payment();
            $payment->setReservation($reservation);
            $payment->setAmount($reservation->getTotalPrice());
            $payment->setMethod($form->get('paymentMethod')->getData());
            $payment->setDate(new \DateTime());

            $em->persist($payment);
            $em->flush();
            // ===========================================

            return $this->redirectToRoute(
                'front_reservation_success',
                ['id' => $reservation->getId()]
            );
        }

        return $this->render('front/reservation/book.html.twig', [
            'form' => $form->createView(),
            'room' => $room
        ]);
    }

    #[Route('/success/{id}', name: 'success')]
    public function success(int $id, ReservationRepository $repo): Response
    {
        $reservation = $repo->find($id);
        if (!$reservation) {
            throw $this->createNotFoundException();
        }

        return $this->render('front/reservation/success.html.twig', [
            'reservation' => $reservation
        ]);
    }
}
