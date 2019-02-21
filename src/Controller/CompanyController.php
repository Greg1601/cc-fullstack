<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;

class CompanyController extends AbstractController
{

    /**
     * @Route("/companyEdit", name="jobLicompanyEditst")
     */
    public function companyEditAction()
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $user = $this->getUser();


        // $jobs = $this ->getDoctrine()
        // ->getManager()
        // ->getRepository(JobOffer::class)
        // ->findAll();

        // // récupération de tous les élements Skill pour affichage si inscription
        // $skills = $this->getDoctrine()
        // ->getManager()
        // ->getRepository(Skill::class)
        // ->findAll();

        // $user = $this->getUser();

        return $this->render('offers.html.twig', 
            [
                'jobs' => $jobs,
                'skills' => $skills,
                'user' => $user
            ]
        );
    }
}