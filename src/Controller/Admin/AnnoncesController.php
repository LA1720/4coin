<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\AnnoncesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;

/**
 * @Route("/admin/annonces", name="admin_annonces_")
 * @package App\Controller\Admin
 */
class AnnoncesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        return $this->render('admin/annonces/index.html.twig', [
            'annonces' => $annoncesRepository->findAll(),
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
