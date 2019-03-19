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
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // $session = new Session();
        // dump($error);die;
        $this->addFlash(
            'notice',
            'Identifiants de connexion invalides!!!'
        );
        
        $referer = $request
        ->headers
        ->get('referer');

        
        if ('http://127.0.0.1:8000/job' === $referer ) {
            return $this->redirectToRoute('jobList');
        }
        if ('http://127.0.0.1:8000/talentProfile' === $referer) {
            return $this->redirectToRoute('TalentList');
        }
        else {
            return $this->redirect($referer);
        }

    }

    // /**
    //  * @Route("/checkMail", name="checkMail")
    //  */
    // public function checkMailAction()
    // {
    //     $_POST['email'];
    //     dump($_POST['email']);die;
        
    //     $testCompanyMails = $this->getDoctrine()
    //                         ->getManager()
    //                         ->getRepository(Company::class)
    //                         ->findOneMail($_POST['email']);

    //     $testTalentMails = $this->getDoctrine()
    //                         ->getManager()
    //                         ->getRepository(Talent::class)
    //                         ->findOneMail($_POST['email']);

    //     $testAdminMails = $this->getDoctrine()
    //                         ->getManager()
    //                         ->getRepository(Admin::class)
    //                         ->findOneMail($_POST['email']);

    //     if ( $testAdminMails == null && $testCompanyMails == null && $testTalentMails == null) {
    //         echo "OK";
    //     }
    //     else {
    //         echo "Adresse email déjà utilisée";
    //     }
    // }

    /**
     * @Route("/logout", name="logout")
     * @Method("POST")
     */
    public function logout(): Response
    {

    }

    /**
     * @Route("/lostPassword", name="lostPassword")
     */
    public function lostPasswordAction()
    {
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('resetPassword.html.twig',[
            'skills' => $skills,
        ]);
    }

    /**
     * @Route("/resetPassword", name="resetPassword")
     * @Method("POST")
     */
    public function resetpasswordAction(Request $request)
    {
        $mail = $request->request->get('email');
        // $user = null;
        
        $testCompany = $this->getDoctrine()
        ->getManager()
        ->getRepository(Company::class)
        ->findOneByMail($mail);
        
        $testTalent = $this->getDoctrine()
        ->getManager()
        ->getRepository(Talent::class)
        ->findOneByMail($mail);
        
        $testAdmin = $this->getDoctrine()
        ->getManager()
        ->getRepository(Admin::class)
        ->findOneByMail($mail);
        
        // dump($testTalent);die;
        if ($testAdmin != null) {
            $user = $testAdmin;
            // dump('admin');die;
        }
        elseif ($testCompany != null) {
            $user = $testCompany;
            // dump('company');die;
        }
        elseif ($testTalent != null) {
            $user = $testTalent;
            // dump('talent');die;
        }

        if ($user) {
            // envoyer le mail à la boite concernée
        }

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        return $this->render('homepages/homepage.html.twig',[
            'skills' => $skills
        ]);
    }

    // /**
    //  * @Route("/newPassword", name="newPassword")
    //  */
    // public function newPasswordAction()
    // {
    //     $skills = $this->getDoctrine()
    //     ->getManager()
    //     ->getRepository(Skill::class)
    //     ->findAll();

    //     return $this->render('resetPassword.html.twig',[
    //         'skills' => $skills,
    //     ]);
    // }

}