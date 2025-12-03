<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/payment', name: 'admin_payment_')]
class PaymentController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('admin/payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($payment);
            $em->flush();

            return $this->redirectToRoute('admin_payment_index');
        }

        return $this->render('admin/payment/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Payment $payment, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('admin_payment_index');
        }

        return $this->render('admin/payment/edit.html.twig', [
            'form' => $form,
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Payment $payment, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->get('_token'))) {
            $em->remove($payment);
            $em->flush();
        }

        return $this->redirectToRoute('admin_payment_index');
    }
}
