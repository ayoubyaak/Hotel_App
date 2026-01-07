<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/about', name: 'front_about')]
class AboutController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('front/about/index.html.twig');
    }
}
