<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

/**
 * @Route("/admin/categories", name="admin_categories_")
 * @package App\Controller\Admin
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }



    /**
     * @Route("/ajout", name="ajout")
     */
    public function ajoutCatagory(Request $request): Response
    {   
        $category = new Categories;
        $form = $this->createForm(CategoriesType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories_home');
        }



        return $this->render('admin/categories/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifierCatagory(Categories $categories, Request $request): Response
    {   
        
        $form = $this->createForm(CategoriesType::class, $categories);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            return $this->redirectToRoute('admin_categories_home');
        }



        return $this->render('admin/categories/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
