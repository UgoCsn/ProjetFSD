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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }


    /*#[Route('/cat/edit/{id}', name: 'app_cat-edit')]
    public function edit(Category $id ,CategoryRepository $categoryRepository,Request $request, EntityManagerInterface $em): Response
    {
        $cat = $categoryRepository->findOneBy(['id'=>$id]);

        $form = $this->createForm(CategoryFormType::class, $categoryRepository);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cat->setName($form->get('name')->getData());
            $em->persist($cat);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }


        return $this->render('category/editCat.html.twig', [
            'form' => $form->createView(),
            'cat' => $cat
        ]);
    }*/
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/category/new/', name: 'app_new-category')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }
}
