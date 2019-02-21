<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Company;

class CompanyController extends AbstractController
{

    /**
     * @Route("/companyRegistration", name="companyRegistration")
     * @Method("POST")
     */

    public function companyRegistration(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $em = $this->getDoctrine()->getManager();
        $company = new Company;
        $company->setName($request->request->get('name'));
        $company->setAddress($request->request->get('address'));
        $company->setMail($request->request->get('email'));
        $company->setPhone($request->request->get('phone'));
        $company->setPassword($encoder->encodePassword($company, $request->request->get('password')));
        $company->setPicture($request->request->get('picture'));
        $company->setUsername($request->request->get('name'));
        
        // Génération d'une clé aléatoire pour l'activation du compte
        // $company->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($company);
        $em->flush();

        return $this->redirectToRoute('homePro'); 
                Response::HTTP_OK
        ;
    }

    /**
     * @Route("/companyEdit", name="companyEdit")
     * @Method("POST")
     */
    public function companyEditAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        // $company = new Company;
        $user->setName($request->request->get('name'));
        $user->setAddress($request->request->get('address'));
        $user->setMail($request->request->get('email'));
        $user->setPhone($request->request->get('phone'));
        // $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
        $user->setPicture($request->request->get('picture'));
        $user->setUsername($request->request->get('name'));
        
        // Génération d'une clé aléatoire pour l'activation du compte
        // $user->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('profile',
            [
                'user' => $user
            ]
        ); 
                Response::HTTP_OK
        ;
    }
}