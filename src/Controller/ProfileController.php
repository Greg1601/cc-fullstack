<?php
namespace App\Controller;

use App\Entity\Talent;
use App\Entity\Skill;
use App\Entity\Company;
use App\Entity\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     * @Method("POST")
     */

    public function profileAction(Request $request)
    {

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        $user = $this->getUser();
        $userMail=$user->getMail();

        if ($user = $this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail)) {

            $talent = $user;
            return $this->render('profileTalent.html.twig', 
            [
                'skills' => $skills,
                'user' => $user,
                'talent' => $talent           ]
            ); 
            Response::HTTP_OK;
        }
        elseif ($user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) {

            $company = $user;
            return $this->render('profileCompany.html.twig', 
            [
                'skills' => $skills,
                'company' => $company,
                'user' => $user             ]
            ); 
            Response::HTTP_OK;
        }
        else {

            $admin = $user;
            return $this->render('profileAdmin.html.twig', 
            [
                'skills' => $skills,
                'admin' => $admin,
                'user' => $user             ]
            ); 
            Response::HTTP_OK;
        }
        
    }

}