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
        // dump($authenticationUtils);die;
        $lastUsername = $authenticationUtils->getLastUsername();
        // $session = new Session();
        // dump($this->getUser());die;
        $this->addFlash(
            'notice',
            'Identifiants de connexion invalides!!!'
        );
        
        $referer = $request
        ->headers
        ->get('referer');

        // dump($referer);die;

        if (null === $referer ) {
            return $this->redirectToRoute('home');
        }
        if ('http://127.0.0.1:8000/job' === $referer ) {
            return $this->redirectToRoute('jobList');
        }
        if ('http://127.0.0.1:8000/talentProfile' === $referer) {
            return $this->redirectToRoute('TalentList');
        }
        else {
            return $this->redirect($referer);
        }

        // return $this->redirectToRoute('404page'); 
        //     Response::HTTP_OK
        //     ;

    }

    /**
     * @Route("/checkMail", name="checkMail")
     */
    public function checkMailAction(Request $request)
    {
        // $_POST['email'];
        // dump($_POST['email']);die;
        
        $testCompanyMails = $this->getDoctrine()
                            ->getManager()
                            ->getRepository(Company::class)
                            ->findOneByMail($_POST['email']);

        $testTalentMails = $this->getDoctrine()
                            ->getManager()
                            ->getRepository(Talent::class)
                            ->findOneByMail($_POST['email']);

        $testAdminMails = $this->getDoctrine()
                            ->getManager()
                            ->getRepository(Admin::class)
                            ->findOneByMail($_POST['email']);

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return new Response ("Vous devez entrer une adresse mail valide");
        }
        elseif ( $testAdminMails == null && $testCompanyMails == null && $testTalentMails == null) {
            return new Response ("Email disponible");
        }
        else {
            return new Response ("Email déjà utilisé");
        }
    }

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
     * @Route("/mailPassword", name="mailPassword")
     * @Method("POST")
     */
    public function mailpasswordAction(Request $request, \Swift_Mailer $mailer)
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
            $message = (new \Swift_Message('Mot de passe oublié - Corse Connexion'))
            ->setFrom('info@corse-connexion.corsica')
            ->setTo($user->getMail())
            ->setBody(
                $this->renderView(
                    'emails/lostPasswordEmail.html.twig',[
                        'user' => $user
                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
        }

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();
        
        return $this->render('homepages/homepage.html.twig',[
            'skills' => $skills
        ]);
    }

    /**
     * @Route("/newPassword", name="newPassword")
     */
    public function newPasswordAction()
    {

        $userMail = $_GET['mail'];
        // dump($userMail);die;
        
        if ($this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail)) {  
            $user = $this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail);
            $usertype = 'Talent';
            
        }
        elseif ($this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) {
            $user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail);
            $usertype = 'Company';
        }
        elseif ($this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail)) {
            $user = $this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail);
            $usertype = 'Admin';
        }

        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        return $this->render('newPassword.html.twig',[
            'skills' => $skills,
            'user' => $user,
            'usertype' => $usertype
        ]);
    }

    /**
     * @Route("/setNewPassword", name="setNewPassword")
     */
    public function setNewPasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $userMail = $request->request->get('mail');
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        if ($this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail)) {  
            $user = $this->getDoctrine()->getManager()->getRepository(Talent::Class)->findOneByMail($userMail);

            $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homeTalent');
        }
        elseif ($this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail)) {
            $user = $this->getDoctrine()->getManager()->getRepository(Company::Class)->findOneByMail($userMail);

            $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('homePro');
        }
        elseif ($this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail)) {
            $user = $this->getDoctrine()->getManager()->getRepository(Admin::Class)->findOneByMail($userMail);
            
            $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homePro');
        }

    }


}