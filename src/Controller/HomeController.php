<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\JobOffer;
use App\Entity\Skill;
use App\Entity\Talent;
use App\Entity\Admin;

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
        $usertype = null;

        if ($user) {
            if ($this->getDoctrine()->getManager()->getRepository(Admin::class)->findOneByMail($user->getMail())) {
                $usertype = 'admin';
            }
        }

        // récupération des éléments de Talents.
        $talents = $this->getDoctrine()
        ->getManager()
        ->getRepository(Talent::class)
        ->findBy([
            'isChecked' => '1'
        ]);

        shuffle($talents);

        //récupération des 3 derniers éléments du tableau $talents mélangé
        $threeTalents = array_slice($talents, 0, 3, true);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        return $this->render('homepages/homePro.html.twig',[
            'talents' => $threeTalents,
            'skills' => $skills,
            'user' => $user,
            'usertype' => $usertype
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
        ->findBy([
            'isFilled' => '0',
            'visibility' => '1',
            'isChecked' => '1'
        ]);

        $usertype=null;

        shuffle($jobs);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        // stockage en variable des 3 premiers éléments du tableau mélangé pour affichage sur la page d'accueil
        $threeOffers = array_slice($jobs, 0, 3, true);
        
        $user = $this->getUser();

        if ($user) {
            if ($this->getDoctrine()->getManager()->getRepository(Admin::class)->findOneByMail($user->getMail())) {
                $usertype = 'admin';
            }
        }
        else {
            $usertype = null;
        }

        // return $this->render('homePro.html.twig');
        return $this->render('homepages/homeTalent.html.twig',[
            'jobs' => $threeOffers,
            'skills' => $skills,
            'user' => $user,
            'usertype' => $usertype
        ]);
    }

    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction()
    {
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('privacy.html.twig',[
            'skills' => $skills,
        ]);
    }

    /**
     * @Route("/CGU", name="CGU")
     */
    public function CGUAction()
    {
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('mentions.html.twig',[
            'skills' => $skills,
        ]);
    }

}