<?php

namespace App\Controller\Admin;

use App\Entity\Banner;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\Seller;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Megano Admin Panel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Category', 'fas fa-question-circle', Category::class);
        yield MenuItem::linkToCrud('Banner', 'fas fa-question-circle', Banner::class);
        yield MenuItem::linkToCrud('User', 'fas fa-question-circle', User::class);
        yield MenuItem::linkToCrud('Seller', 'fas fa-question-circle', Seller::class);
        yield MenuItem::linkToCrud('Product', 'fas fa-question-circle', Product::class);
        yield MenuItem::linkToCrud('Review', 'fas fa-question-circle', Review::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
