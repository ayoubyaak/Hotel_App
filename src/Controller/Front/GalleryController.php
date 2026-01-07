<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gallery', name: 'front_gallery')]
class GalleryController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('front/gallery/index.html.twig');
    }
}
