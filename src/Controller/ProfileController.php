<?php
namespace App\Controller;

use App\Entity\Talent;
use App\Entity\Skill;
use App\Entity\Company;
use App\Entity\Admin;
use App\Entity\JobOffer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{

    /**
     * @Route("/monProfil", name="myProfile")
     * @Method("POST")
     */

    public function myProfileAction(Request $request)
    {

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        $user = $this->getUser();
        
        $userMail=$user->getMail();

        if ($user = $this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail)) {

            // $talentSkills = $user->getSkills();
            // dump($user->getAvatar());die;


            //img/users/pictures/a9553b6dc810751e7d6d1b55e4c68574.png
            
            return $this->render('profilePages/profileTalent.html.twig', 
                [
                'skills' => $skills,
                'talent' => $user,
                'company' => null,
                'user' => $user       
                ]
            ); 
            Response::HTTP_OK;
        }
        elseif ($user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) {

            return $this->render('profilePages/profileCompany.html.twig', 
                [
                'user' => $user            
                 ]
            ); 
            Response::HTTP_OK;
        }
        elseif ($user = $this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail)) {
            // dump($user);die;

            return $this->render('profilePages/profileAdmin.html.twig', 
                [
                'user' => $user             
                ]
            ); 
            Response::HTTP_OK;
        }
        
    }

    /**
     * @Route("/job", name="jobProfile")
     */
    public function jobProfileAction(Request $request)
    {   

        $id = $request->request->get('id');

        $job = $this->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findOneById($id);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        // dump($job);die;

        if ($this->getUser())
        {
            $user = $this->getUser();
            
            $userMail= $user->getMail();
            // dump($user);die;
            if ($user = $this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail)) {  
                $usertype = 'talent';               
                return $this->render('profilePages/profileOffer.html.twig', 
                    [
                        'job' => $job,
                        'skills' => $skills,
                        'user' => $user,
                        'usertype' => $usertype                     
                    ]
                ); 
                Response::HTTP_OK;
            }
            elseif ($user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) {
                $usertype = 'company';
                return $this->render('profilePages/profileOffer.html.twig', 
                    [
                        'job' => $job,
                        'skills' => $skills,
                        'user' => $user,
                        'usertype' => $usertype              
                    ]
                ); 
                Response::HTTP_OK;
            }
            elseif ($user = $this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail)) {
                $usertype = 'admin';
                return $this->render('profilePages/profileOffer.html.twig', 
                    [
                        'job' => $job,
                        'skills' => $skills,
                        'user' => $user,
                        'usertype' => $usertype                
                    ]
                ); 
                Response::HTTP_OK;
            }
        }
        
        elseif (!$this->getUser()) 
        {
            return $this->render('profilePages/profileOffer.html.twig', 
                [
                    'job' => $job,
                    'skills' => $skills,
                    'user' => null           
                ]
            ); 
            Response::HTTP_OK;
        }
    }

    /**
     * @Route("/talentProfile", name="talentProfile")
     */
    public function talentProfileAction(Request $request)
    {   
        $id = $request->request->get('id');
        
        $talent = $this->getDoctrine()
        ->getManager()
        ->getRepository(Talent::class)
        ->findOneById($id);
        
        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        if ($this->getUser())
        {            
            $user = $this->getUser();
            
            $userMail = $user->getMail();
            
            if ($user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) 
            {
                $company = $user;
            }
            else 
            {
                $company = null;
            }

            return $this->render('profilePages/profileTalent.html.twig', 
                [
                    'talent' => $talent,
                    'skills' => $skills,
                    'user' => $user,
                    'company' => $company           
                ]
            ); 
            Response::HTTP_OK;

        }
        else 
        {
            $user = null;

            return $this->render('profilePages/profileTalent.html.twig', 
                [
                    'talent' => $talent,
                    'skills' => $skills,
                    'user' => $user          
                ]
            ); 
            Response::HTTP_OK;
        }
        dump($company);die;

    }
    

}