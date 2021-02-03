<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/users", name="users_")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig');
    }


    /**
     * @Route("/annonces/ajout", name="annonces_ajout")
     */
    public function ajoutAnnonces(Request $request): Response
    {   
        $annonce = new Annonces;
        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

             $annonce->setUsers($this->getUser());
             $annonce->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('users_home');
        }


        return $this->render('users/annonces/ajout.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
