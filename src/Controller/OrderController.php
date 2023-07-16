<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="app_order")
     */
    public function order(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('order/order.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/order/payment", name="app_order_payment")
     */
    public function payment(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('order/payment.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/order/payment-someone", name="app_order_payment_someone")
     */
    public function paymentSomeone(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('order/paymentsomeone.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/order/payment/progress", name="app_order_payment_progress")
     */
    public function paymentProgress(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('order/paymentprogress.html.twig', [
            'categories' => $categories
        ]);
    }
}