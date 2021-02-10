<?php

namespace App\Controller;


use App\Repository\AnnoncesRepository;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/annonces", name="annonces_")
 * @package App\Controller
 */

class AnnoncesController extends AbstractController
{
   
    /**
     * @Route("/details/{slug}", name="details")
     */
    public function details($slug, AnnoncesRepository $annoncesRepo)
    {
        $annonce = $annoncesRepo->findOneBy(['slug' => $slug]);

        if(!$annonce){
            throw new NotFoundHttpException('Pas d\'annonce trouvée');
        }

        return $this->render('annonces/details.html.twig', compact('annonce'));
    }


}
