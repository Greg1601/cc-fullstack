<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;
use App\Entity\Skill;

class JobController extends AbstractController
{

    /**
     * @Route("/jobs", name="jobList")
     */
    public function jobListAction()
    {

        // récupération de tous les éléments de JobOffer pour affichage.
        $jobs = $this ->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findAll();

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        // dump($skills);die;

        return $this->render('offers.html.twig', 
            [
                'jobs' => $jobs,
                'skills' => $skills
            ]
        );
    }
}