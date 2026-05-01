<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Cart\CartHandler;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();
        return $this->render('page/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('page/profile.html.twig');
    }

    #[Route('/categories', name: 'categories')]
    public function categories(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('page/browse_categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/cart', name: 'cart')]
    public function cart(CartHandler $cartHandler, EntityManagerInterface $em): Response
    {
        $items = $cartHandler->getItems();
        $products = [];
        foreach ($items as $productId => $quantity) {
            $products[] = [
                'product' => $em->getRepository(Product::class)->find($productId),
                'quantity' => $quantity,
            ];
        }
        return $this->render('page/cart.html.twig', [
            'items' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'product_details')]
    public function productDetails(int $id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        return $this->render('page/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/category/{id}', name: 'products_by_category')]
    public function productsByCategory(int $id, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->find($id);
        $products = $em->getRepository(Product::class)->findBy(['category' => $category]);
        return $this->render('page/products_by_category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, CartHandler $cartHandler): Response
    {
        $cartHandler->add($id, 1);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function removeFromCart(int $id, CartHandler $cartHandler): Response
    {
        $cartHandler->remove($id);
        return $this->redirectToRoute('cart');
    }
}