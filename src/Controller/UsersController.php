<?php

namespace App\Controller;

use App\Entity\Images;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\EditProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function ajoutAnnonce(Request $request)
    {
        $annonce = new Annonces;

        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $annonce->setUsers($this->getUser());
            $annonce->setActive(false);
            // On récupère les images transmises
            $images = $form->get('images')->getData();
                
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $img = new Images();
                $img->setName($fichier);
                $annonce->addImage($img);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('users_home');
        }

        return $this->render('users/annonces/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    


    /**
     * @Route("/profile/modifier", name="profile_modifier")
     */
    public function editProfile(Request $request): Response
    {   
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'profile mis à jour');

            return $this->redirectToRoute('users_home');
        }


        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/pass/modifier", name="pass_modifier")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {   
        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();

                $this->addFlash('message', 'Mot de pass mis à jour avec succès');

                return $this->redirectToRoute('users_home');

                 

            }else{
                $this->addFlash('error', 'Les mots de passe ne sont pas identiques');
            }
        }


        return $this->render('users/editpassword.html.twig');
    }

}
