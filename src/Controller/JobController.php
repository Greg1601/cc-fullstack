<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        ->findBy([
            'isFilled' => '0',
            'visibility' => '1',
            'isChecked' => '1'
        ]);
        
        shuffle($jobs);

        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        $user = $this->getUser();

        return $this->render('offers.html.twig', 
            [
                'jobs' => $jobs,
                'skills' => $skills,
                'user' => $user
            ]
        );
    }

    /**
     * @Route("/offerEdit", name="offerEdit")
     * @Method("POST")
     */
    public function offerEditAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();
        
        $offer = $this->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findOneById($request->request->get('id'));
        $offer->setJobName($request->request->get('jobName'));
        $offer->setjobDescription($request->request->get('jobDescription'));
        $offer->setRequiredExperience($request->request->get('requiredExperience'));
        $offer->setSalary($request->request->get('salary'));
        $offer->setJobPlace($request->request->get('jobPlace'));
        $offer->setContact($request->request->get('contact'));
        if ($request->request->get('remotePossibility')) {
            $offer->setRemotePossibility($request->request->get('remotePossibility'));
        }
        else {
            $offer->setRemotePossibility(0);
        }
        
        if ($request->request->get('freelancePossibility')) {
            $offer->setFreelancePossibility($request->request->get('freelancePossibility'));
        }
        else {
            $offer->setFreelancePossibility(0);
        }

        if ($request->files->get('jobSheet')) {
            $file = $request->files->get('jobSheet');
            $jobSheetName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $cv = $file->move(
                $this->getParameter('userjobSheet_directory'),
                $jobSheetName
            );
            dump($file);die;
            $offer->setJobSheet('img/users/jobSheet/'.$jobSheetName);
        }

        $em->persist($offer);
        $em->flush();

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('profilePages/profileOffer.html.twig', 
            [
                'job' => $offer,
                'skills' =>  $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Skill::class)
                    ->findAll(),
                'user' => $this->getUser(),
                'usertype' => 'company'                
            ]
        ); 
        Response::HTTP_OK;
    }

    /**
     * @Route("/offerHide", name="offerHide")
     * @Method("POST")
     */
    public function offerHideAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();
        
        $offer = $this->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findOneById($request->request->get('id'));
        $offer->setVisibility(0);

        $em->persist($offer);
        $em->flush();

        return $this->render('profilePages/profileCompany.html.twig', 
            [
                'user' => $this->getUser()              
            ]
        ); 
        Response::HTTP_OK;
    }

    /**
     * @Route("/offerFilled", name="offerFilled")
     * @Method("POST")
     */
    public function offerFilledAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();
        
        $offer = $this->getDoctrine()
        ->getManager()
        ->getRepository(JobOffer::class)
        ->findOneById($request->request->get('id'));
        $offer->setIsFilled(1);

        $em->persist($offer);
        $em->flush();

        return $this->render('profilePages/profileCompany.html.twig', 
            [
                'user' => $this->getUser()              
            ]
        ); 
        Response::HTTP_OK;
    }

    /**
     * @Route("/addOpportunity", name="addOpportunity")
     * @Method("POST")
     */
    public function addOpportunityAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();
        
        $offer = new JobOffer;
        
        $offer->setJobName($request->request->get('jobName'));
        $offer->setjobDescription($request->request->get('jobDescription'));
        $offer->setRequiredExperience($request->request->get('requiredExperience'));
        $offer->setSalary($request->request->get('salary'));
        $offer->setJobPlace($request->request->get('jobPlace'));
        $offer->setContact($request->request->get('contact'));
        $offer->setCompany($this->getUser());
        if ($request->request->get('remotePossibility')) {
            $offer->setRemotePossibility($request->request->get('remotePossibility'));
        }
        else {
            $offer->setRemotePossibility(0);
        }

        if ($request->request->get('freelancePossibility')) {
            $offer->setFreelancePossibility($request->request->get('freelancePossibility'));
        }
        else {
            $offer->setFreelancePossibility(0);
        }
        
        if ($request->files->get('jobSheet')) {
            $file = $request->files->get('jobSheet');
            $jobSheetName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $cv = $file->move(
                $this->getParameter('userjobSheet_directory'),
                $jobSheetName
            );
            $offer->setJobSheet('img/users/jobSheets/'.$jobSheetName);
        }

        $em->persist($offer);
        $em->flush();

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('profilePages/profileOffer.html.twig', 
            [
                'job' => $offer,
                'skills' =>  $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Skill::class)
                    ->findAll(),
                'user' => $this->getUser(),
                'usertype' => 'company'                
            ]
        ); 
        Response::HTTP_OK;
    }
    
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}