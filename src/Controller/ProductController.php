<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryFormType;
use App\Form\ItemFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $productRepository): Response
    {
        $allProduct = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'allProduct' => $allProduct,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/delete/{id}', name: 'app_product-delete')]
    public function delete(Product $id,EntityManagerInterface $em): Response
    {
        $em->remove($id);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/edit/{id}', name: 'app_product-edit')]
    public function edit(Product $id ,ProductRepository $productRepository,Request $request, EntityManagerInterface $em): Response
    {
        $product = $productRepository->findOneBy(['id'=>$id]);

        $form = $this->createForm(ItemFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if($image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $product->setImage($fichier);
            }
            $price = ($form->get('price')->getData());
            $product->setPrice($price);
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }


        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/product/new', name: 'app_new_product')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ItemFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $fichier = md5(uniqid()).'.'.$image->guessExtension();
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );
            $product->setImage($fichier);

            $price = ($form->get('price')->getData())*100;
            $product->setPrice($price);
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }


        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/detail/{id}', name: 'app_detail')]
    public function detail(String $id , ProductRepository  $productRepository): Response
    {
        $product = $productRepository->findOneBy(['id'=>$id]);


        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/{categoryName}', name: 'app_filter')]
    public function filter(EntityManagerInterface $em ,$categoryName): Response
    {
        $category = $em->getRepository(Category::class)->findOneBy(['name'=>$categoryName]);
        $productFilter = $em->getRepository(Product::class)->findBy(['category'=>$category]);

        return $this->render('product/filter.html.twig', [
            'productFilter' => $productFilter,
        ]);
    }

   /* #[Route('/product/search/{s}', name: 'app_search')]
    public function search(EntityManagerInterface $em ,string $s): JsonResponse
    {
        $products = $em->getRepository(Product::class)->findBySearch($s);

        return $this->json($products, 200,[]);
    }*/

    #[Route('/search/{userSearch}', name: 'app_search')]
    public function search( EntityManagerInterface $em, string $userSearch ) : JsonResponse
    {
        if( $userSearch )
        {
            $searchResult = $em->getRepository(Product::class)->findOneBy(['name'=>$userSearch]); // cherche les produits
        }
        else
        {
            $searchResult = $em->getRepository(Product::class)->findAll(); // cherche les produits // tous les produits
        }

        // doc SF : return $this->json($data, $status = 200, $headers = [], $context = []);

        return $this->json( $searchResult, 200, [], ['groups' => ['search']] );
    }
}
