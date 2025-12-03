<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin/room', name: 'admin_room_')]
class RoomController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(RoomRepository $roomRepository): Response
    {
        return $this->render('admin/room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si l'image est envoyée, on la traite (optionnel pour toi)
            $image = $form->get('imageFile')->getData() ?? null;
            if ($image) {
                $newName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('room_images_dir'), $newName);
                $room->setImage($newName);
            }

            $em->persist($room);
            $em->flush();

            $this->addFlash('success', 'Chambre ajoutée avec succès.');
            return $this->redirectToRoute('admin_room_index');
        }

        return $this->render('admin/room/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Room $room, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Traitement image si nouvelle image
            $image = $form->get('imageFile')->getData() ?? null;
            if ($image) {
                $newName = uniqid().'.'.$image->guessExtension();
                $image->move($this->getParameter('room_images_dir'), $newName);
                $room->setImage($newName);
            }

            $em->flush();

            $this->addFlash('success', 'Chambre mise à jour avec succès.');
            return $this->redirectToRoute('admin_room_index');
        }

        return $this->render('admin/room/edit.html.twig', [
            'form' => $form,
            'room' => $room,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Room $room, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->get('_token'))) {

            $em->remove($room);
            $em->flush();

            $this->addFlash('success', 'Chambre supprimée.');
        }

        return $this->redirectToRoute('admin_room_index');
    }
}
