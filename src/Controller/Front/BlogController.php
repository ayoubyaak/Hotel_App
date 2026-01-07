<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/blog', name: 'front_blog')]
class BlogController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->render('front/blog/index.html.twig');
    }
}
