<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;
use App\Entity\Skill;

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

        // récupération des éléments de JobOffer pour récupérer les derniers créés en priorité.
        $jobs = $this ->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findBy(array(), array('id' => 'desc'));

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        // stockage en variable des 3 premiers éléments du tableau récupéré pour affichage sur la page d'accueil (les 3 dernières offres    entrées en BDD seront affichées pour exemple sur la homepage)
        $lastThree = array_slice($jobs, 0, 3, true);

        
        return $this->render('homepages/homePro.html.twig',[
            'jobs' => $lastThree,
            'skills' => $skills,
            'user' => $user
        ]);
    }

    /**
     * @Route("/talent", name="homeTalent")
     */
    public function homeTalentAction()
    {

        // récupération des éléments de JobOffer pour récupérer les derniers créés en priorité.
        $jobs = $this ->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findBy(array(), array('id' => 'desc'));

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        // stockage en variable des 3 premiers éléments du tableau récupéré pour affichage sur la page d'accueil (les 3 dernières offres    entrées en BDD seront affichées pour exemple sur la homepage)
        $lastThree = array_slice($jobs, 0, 3, true);
        
        $user = $this->getUser();

        // return $this->render('homePro.html.twig');
        return $this->render('homepages/homeTalent.html.twig',[
            'jobs' => $lastThree,
            'skills' => $skills,
            'user' => $user
        ]);
    }

}