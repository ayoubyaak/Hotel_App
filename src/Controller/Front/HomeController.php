<?php
namespace App\Controller\Front;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('', name: 'front_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RoomRepository $roomRepo): Response
    {
        $rooms = $roomRepo->findBy(['available' => true]);

        return $this->render('front/home/index.html.twig', [
            'rooms' => $rooms
        ]);
    }
}
