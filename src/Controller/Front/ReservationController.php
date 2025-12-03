<?php
namespace App\Controller\Front;

use App\Entity\Client;
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
    public function book(int $roomId, Request $request, RoomRepository $roomRepo, ReservationRepository $resRepo, EntityManagerInterface $em): Response
    {
        $room = $roomRepo->find($roomId);
        if (!$room) {
            throw $this->createNotFoundException('Chambre non trouvée');
        }

        $reservation = new Reservation();
        $form = $this->createForm(FrontReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // reservation entity fields: startDate, endDate, maybe client object or client fields

            // Vérifier disponibilité
            $start = $reservation->getStartDate();
            $end = $reservation->getEndDate();
            if (!$resRepo->isRoomAvailable($room->getId(), $start, $end)) {
                $this->addFlash('danger', 'La chambre n\'est pas disponible sur ces dates.');
                // show form again
                return $this->render('front/reservation/book.html.twig', [
                    'form' => $form->createView(),
                    'room' => $room
                ]);
            }

            // Si formulaire contient des infos client (guest), créer Client
            $clientData = $form->get('guestFullName')->getData();
            if ($clientData) {
                $client = new Client();
                $client->setFullName($form->get('guestFullName')->getData());
                $client->setEmail($form->get('guestEmail')->getData());
                $client->setPhone($form->get('guestPhone')->getData());
                // CIN optional if in form
                $client->setCin($form->get('guestCin')->getData() ?? '');
                $em->persist($client);
                $reservation->setClient($client);
            } else {
                // Option: if user logged in, set client to current user's client profile (depends on design)
            }

            $reservation->setRoom($room);

            // Calcul prix total
            $days = $start->diff($end)->days;
            if ($days === 0) $days = 1;
            $reservation->setTotalPrice($days * $room->getPrice());

            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation effectuée avec succès.');
            return $this->redirectToRoute('front_reservation_success', ['id' => $reservation->getId()]);
        }

        return $this->render('front/reservation/book.html.twig', [
            'form' => $form->createView(),
            'room' => $room
        ]);
    }

    #[Route('/success/{id}', name: 'success')]
    public function success(int $id, ReservationRepository $resRepo): Response
    {
        $reservation = $resRepo->find($id);
        if (!$reservation) throw $this->createNotFoundException();
        return $this->render('front/reservation/success.html.twig', ['reservation' => $reservation]);
    }
}
