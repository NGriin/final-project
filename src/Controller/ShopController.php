<?php

namespace App\Controller;

use App\Entity\Banner;
use App\Entity\Category;
use App\Entity\Features;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductSeller;
use App\Entity\Review;
use App\Entity\Seller;
use App\Repository\BannerRepository;
use App\Repository\CategoryRepository;
use App\Repository\FeaturesRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductSellerRepository;
use App\Repository\SellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ShopController extends AbstractController
{
    /**
     * @Route("/", name="app_shop_homepage", methods={"GET"})
     */
    public function showHomepage(EntityManagerInterface $em, CategoryRepository $categoryRepository, BannerRepository $bannerRepository, ProductRepository $productRepository, ProductImageRepository $productImageRepository, ProductSellerRepository $productSellerRepository)
    {
        $banners = $bannerRepository->findBy(['is_active' => true]);
        $bannerTitle = $bannerRepository->getAllCategoryTitles();
        $numFromTitle = $bannerRepository->findNumbersInTitles($bannerTitle);
        $categories = $categoryRepository->getCategories();
        $topProducts = $productRepository->findEightTopProducts();

        $images = $productImageRepository->findBy(['product' => $topProducts]);
        return $this->render('index.html.twig', [
            'categories' => $categories,
            'banners' => $banners,
            'numsFromTitle' => $numFromTitle,
            'topProducts' => $topProducts,
            'images' => $images,
            'productSeller' => $productSellerRepository,
            'categoryProduct' => $categoryRepository,
        ]);
    }

    /**
     * @Route("/about", name="app_shop_about", methods={"GET"})
     */
    public function showAboutPage(CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $banners = $em->getRepository(Banner::class);
        $bannerTitle = $banners->getTitle();
        $categories = $categoryRepository->getCategories();
        return $this->render('main/about.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/contacts", name="app_shop_contacts", methods={"GET"})
     */
    public function showContacts(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $repository = $em->getRepository(Category::class);
        $categories = $categoryRepository->getCategories();
        return $this->render('main/contacts.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/products/{slug}", name="app_show_product", methods={"GET"})
     */
    public function showProductPage(EntityManagerInterface $em, CategoryRepository $categoryRepository, $slug, ProductImageRepository $productImageRepository, Product $productEn)
    {
        $productsSellers = $em->getRepository(ProductSeller::class)->getByProductSlug($slug);
        $product = $em->getRepository(Product::class)->findOneBySlug($slug);
        $reviews = $em->getRepository(Review::class)->getByProductSlug($slug);
        $catId = $product->getCategory()->getId();
        $categoryProduct = $em->getRepository(Category::class)->findCategory($catId);
        $images = $productImageRepository->findBy(['product' => $product]);
        $price = $em->getRepository(ProductSeller::class)->getAVGDiscountByProductSlug($slug);
        $oldPrice = $em->getRepository(ProductSeller::class)->getAVGByProductSlug($slug);
        $repository = $em->getRepository(Category::class);
        $categories = $categoryRepository->getCategories();
        return $this->render('products/product.html.twig', [
            'categories' => $categories,
            'productsSellers' => $productsSellers,
            'AVGPrice' => $price,
            'product' => $product,
            'AVGOldPrice' => $oldPrice,
            'images' => $images,
            'categoryProduct' => $categoryProduct,
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/compare", name="app_show_compare", methods={"GET"})
     */
    public function showComparePage(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $repository = $em->getRepository(Category::class);
        $categories = $categoryRepository->getCategories();
        return $this->render('products/compare.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/sale", name="app_show_sale", methods={"GET"})
     */
    public function showSalePage(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $repository = $em->getRepository(Category::class);
        $categories = $categoryRepository->getCategories();
        return $this->render('products/sale.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/catalog/{sort}/{category}", name="app_show_catalog", defaults={"category"=null})
     */
    public function showCatalogPage(
        Request                $request,
        EntityManagerInterface $em,
        PaginatorInterface     $paginator,
                               $category,
        $sort
    )
    {
        if ($request->getMethod() == 'POST') {
            $price = $request->get('price');
            $title = $request->get('title');
            $seller = $request->get('seller');
            $features = $request->get('features');
            $products = $em->getRepository(Product::class)->findAllByFilters($title, $category, $price, $seller, $features, $sort);
        } else {
            $products = $em->getRepository(Product::class)->findAllWithSearchQuery($request->query->get('q'), $category, $sort);
        }

        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            8
        );

        $sellers = $em->getRepository(Seller::class)->findAll();
        $features = $em->getRepository(Features::class)->findAll();
        $categories = $em->getRepository(Category::class)->getCategories();
        $images = $em->getRepository(ProductImage::class)->findBy(['product' => $products]);
        return $this->render('products/catalog.html.twig', [
            'categories' => $categories,
            'paginator' => $pagination,
            'images' => $images,
            'productSeller' => $em->getRepository(ProductSeller::class),
            'categoryProduct' => $em->getRepository(Category::class),
            'features' => $features,
            'sellers' => $sellers
        ]);
    }

    /**
     * @Route("/shop/{slug}", name="app_show_shop", methods={"GET"})
     */
    public function showShopPage(EntityManagerInterface $em, CategoryRepository $categoryRepository, SellerRepository $sellerRepository, $slug)
    {
        $seller = $sellerRepository->findOneBySlug($slug);
        $categories = $categoryRepository->getCategories();
        return $this->render('products/shop.html.twig', [
            'categories' => $categories,
            'seller' => $seller,
        ]);
    }

    /**
     * @Route("/cart", name="app_show_cart")
     */
    public function cart(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $repository = $em->getRepository(Category::class);
        $categories = $categoryRepository->getCategories();
        return $this->render('main/cart.html.twig', [
            'categories' => $categories
        ]);
    }
}