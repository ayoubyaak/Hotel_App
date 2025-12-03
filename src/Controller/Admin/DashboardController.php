<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ClientRepository;
use App\Repository\RoomRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/dashboard', name: 'admin_dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        CategoryRepository $categoryRepository,
        ClientRepository $clientRepository,
        RoomRepository $roomRepository,
        ReservationRepository $reservationRepository
    ): Response {

        // Statistiques simples
        $stats = [
            'categories'   => $categoryRepository->count([]),
            'clients'      => $clientRepository->count([]),
            'rooms'        => $roomRepository->count([]),
            'reservations' => $reservationRepository->count([]),
        ];

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats
        ]);
    }
}

