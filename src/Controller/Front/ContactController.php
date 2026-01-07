<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact', name: 'front_contact')]
class ContactController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('front/contact/index.html.twig');
    }
}
