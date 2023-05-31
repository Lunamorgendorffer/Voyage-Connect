<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // fonction ajout + edit une category
    #[Route('/category/add', name: 'add_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]
    public function add(EntityManagerInterface $entityManager, Category $category = null, Request $request): Response 
    {
        if (!$category){ // si la category n'existe pas 
            $category = new category();  // alors crée un nouvel objet category 
        }
        // on crée le formulaire 
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        //quand on sousmet le formulaire 
        if($form->isSubmitted() && $form->isValid()){

            $category = $form->getData();
            $entityManager->persist($category);// = prepare
            $entityManager->flush();// execute, on envoie les données dans la db 

            return $this->redirectToRoute('app_category');

        }

        // vue pour afficher le formulaire 
        return $this->render('category/addcategory.html.twig', [
           'formAddcategory' => $form->createView(),
           'edit'=> $category->getId(),
            
        ]);

    }

    // fonction delete d'une category 
    #[Route('/category/{id}/delete', name: 'delete_category')]
    public function delete(EntityManagerInterface $entityManager, Category $category): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('app_category');

    }

    // fonction pour afficher la page detail de la category 
    #[Route('/category/{id}', name: 'show_category')]
    public function show(Category $category): Response
    {
       

        // Retourne sur la vue 'category/detailcategory.html.twig' avec les données suivantes
        return $this->render('category/detailcategory.html.twig', [
        'category' => $category,             // La category à afficher
        ]);
    }
}
