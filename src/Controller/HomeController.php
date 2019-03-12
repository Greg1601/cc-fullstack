<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;
use App\Entity\Skill;
use App\Entity\Talent;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */    
    public function homeAction()
    {

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        $user = $this->getUser();

        return $this->render('homepages/homepage.html.twig',[
            'skills' => $skills,
            'user' => $user
        ]);

    }

    /**
     * @Route("/pro", name="homePro")
     */
    public function homeProAction()
    {
        $user = $this->getUser();
        // dump($user);die;

        // récupération des éléments de Talents.
        $talents = $this->getDoctrine()
        ->getManager()
        ->getRepository(Talent::class)
        ->findAll();

        // Shuffle de talent
        shuffle($talents);

        //récupération des 3 derniers éléments du tableau $talentsq mélangé
        $threeTalents = array_slice($talents, 0, 3, true);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        
        return $this->render('homepages/homePro.html.twig',[
            'talents' => $threeTalents,
            'skills' => $skills,
            'user' => $user
        ]);
    }

    /**
     * @Route("/talent", name="homeTalent")
     */
    public function homeTalentAction()
    {

        // récupération des éléments de JobOffer.
        $jobs = $this ->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findAll();

        //shuffle JobOffer
        shuffle($jobs);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        // stockage en variable des 3 premiers éléments du tableau mélangé pour affichage sur la page d'accueil
        $threeOffers = array_slice($jobs, 0, 3, true);
        
        $user = $this->getUser();

        // return $this->render('homePro.html.twig');
        return $this->render('homepages/homeTalent.html.twig',[
            'jobs' => $threeOffers,
            'skills' => $skills,
            'user' => $user
        ]);
    }

}