<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    /**
     * @Route("/account/", name="app_account")
     * @method User|null getUser()
     */
    public function index(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $user = $this->getUser();

        $categories = $categoryRepository->getCategories();
        return $this->render('account/account.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/account/hystory-order", name="app_account_historyorder")
     */
    public function historyOrder(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('account/historyorder.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/account/hystory-view", name="app_account_historyview")
     */
    public function historyView(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('account/historyview.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/account/one-order", name="app_account_one_order")
     */
    public function oneOrder(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('account/oneorder.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/account/edit", name="app_account_edit")
     */
    public function edit(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->getCategories();
        return $this->render('account/profile.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/create-review", name="app_create_review", methods={"POST"})
     */
    public function createReview(Request $request, EntityManagerInterface $em)
    {
        $product = $em->getRepository(Product::class)->find($request->get('productId'));
        if($product) {
            $review = new Review();
            $review->setAuthorName($request->get('name'))
                ->setEmail($request->get('email'))
                ->setText($request->get('review'))
                ->setCreatedAt(new \DateTimeImmutable())
            ->setProduct($product);
            $em->persist($review);
            $em->flush();
        }
        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }
}