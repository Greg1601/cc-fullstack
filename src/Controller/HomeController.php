<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */    
    public function homeAction()
    {

        return $this->render('homepage.html.twig');
    }

    /**
     * @Route("/pro", name="homePro")
     */
    public function homeProAction()
    {

        // récupération des éléments de JobOffer pour récupérer les derniers créés en priorité.
        $jobs = $this ->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findBy(array(), array('id' => 'desc'));
        
        // stockage en variable des 3 premiers éléments du tableau récupéré pour affichage sur la page d'accueil (les 3 dernières offres    entrées en BDD seront affichées pour exemple sur la homepage)
        $lastThree = array_slice($jobs, 0, 3, true);

        return $this->render('homePro.html.twig');
        // return $this->render('home.html.twig', ['jobs' => $lastThree]);
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
        
        // stockage en variable des 3 premiers éléments du tableau récupéré pour affichage sur la page d'accueil (les 3 dernières offres    entrées en BDD seront affichées pour exemple sur la homepage)
        $lastThree = array_slice($jobs, 0, 3, true);

        // return $this->render('homePro.html.twig');
        return $this->render('homeTalent.html.twig', ['jobs' => $lastThree]);
    }

}