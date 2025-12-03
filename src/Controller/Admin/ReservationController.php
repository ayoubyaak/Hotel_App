<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/reservation', name: 'admin_reservation_')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('admin/reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // ⚠️ Calcul du prix total (optionnel — dépend de Room->price)
            if ($reservation->getRoom() && $reservation->getStartDate() && $reservation->getEndDate()) {
                $days = $reservation->getStartDate()->diff($reservation->getEndDate())->days;
                $reservation->setTotalPrice($days * $reservation->getRoom()->getPrice());
            }

            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation ajoutée avec succès.');
            return $this->redirectToRoute('admin_reservation_index');
        }

        return $this->render('admin/reservation/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Reservation $reservation, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Mise à jour du prix total
            if ($reservation->getRoom() && $reservation->getStartDate() && $reservation->getEndDate()) {
                $days = $reservation->getStartDate()->diff($reservation->getEndDate())->days;
                $reservation->setTotalPrice($days * $reservation->getRoom()->getPrice());
            }

            $em->flush();

            $this->addFlash('success', 'Réservation modifiée avec succès.');
            return $this->redirectToRoute('admin_reservation_index');
        }

        return $this->render('admin/reservation/edit.html.twig', [
            'form' => $form,
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Reservation $reservation, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->get('_token'))) {
            $em->remove($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_reservation_index');
    }
}
