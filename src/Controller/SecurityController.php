<?php
namespace App\Controller;

use App\Entity\Talent;
use App\Entity\Skill;
use App\Entity\Company;
use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @Method("POST")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // $session = new Session();
        
        // dump($session);die;
        return $this->redirectToRoute('404page'); 
            Response::HTTP_OK
            ;
    }

    /**
     * @Route("/logout", name="logout")
     * @Method("POST")
     */
    public function logout(): Response
    {

    }

    /**
     * @Route("/talentRegistration", name="talentRegistration")
     * @Method("POST")
     */

    public function talentRegistration(Request $request, UserPasswordEncoderInterface $encoder)
    {

        
        $em = $this->getDoctrine()->getManager();
        $talent = new Talent;
        $talent->setLastname($request->request->get('lastname'));
        $talent->setFirstname($request->request->get('firstname'));
        $talent->setMail($request->request->get('email'));
        $talent->setPassword($encoder->encodePassword($talent, $request->request->get('password')));
        $talent->setLocation($request->request->get('location'));
        $talent->setAvatar($request->request->get('avatar'));
        $talent->setCV($request->request->get('cv'));
        if ($request->request->get('mobility')) {
            $talent->setIsMobile($request->request->get('mobility'));
        }
        else {
            $talent->setIsMobile(0);
        }
        $talent->setLinkedIn($request->request->get('linkedIn'));

        $skills = $request->request->get('skills');
        foreach ($skills as $s) {
            $skill = $this->getDoctrine()
            ->getManager()
            ->getRepository(Skill::class)
            ->findOneByName($s);
            $talent->addSkill($skill);
        }

        // Génération d'une clé aléatoire pour l'activation du compte
        // $talent->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($talent);
        $em->flush();

        return $this->redirectToRoute('homeTalent'); 
                Response::HTTP_OK
        ;
    }

    
    /**
     * @Route("/companyRegistration", name="companyRegistration")
     * @Method("POST")
     */

    public function companhyRegistration(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $em = $this->getDoctrine()->getManager();
        $company = new Company;
        $company->setName($request->request->get('name'));
        $company->setAddress($request->request->get('address'));
        $company->setMail($request->request->get('email'));
        $company->setPhone($request->request->get('phone'));
        $company->setPassword($encoder->encodePassword($company, $request->request->get('password')));
        $company->setPicture($request->request->get('picture'));
        
        // Génération d'une clé aléatoire pour l'activation du compte
        // $company->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($company);
        $em->flush();

        return $this->redirectToRoute('homePro'); 
                Response::HTTP_OK
        ;
    }

}