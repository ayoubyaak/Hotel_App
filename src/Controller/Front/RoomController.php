<?php
namespace App\Controller\Front;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/rooms', name: 'front_room_')]
class RoomController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RoomRepository $roomRepo): Response
    {
        $rooms = $roomRepo->findAll();
        return $this->render('front/room/index.html.twig', ['rooms' => $rooms]);
    }

    #[Route('/{id}', name: 'show')]
    public function show($id, RoomRepository $roomRepo): Response
    {
        $room = $roomRepo->find($id);
        if (!$room) {
            throw $this->createNotFoundException('Chambre introuvable');
        }
        return $this->render('front/room/show.html.twig', ['room' => $room]);
    }
}
