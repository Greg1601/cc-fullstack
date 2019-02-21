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

}