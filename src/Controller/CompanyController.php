<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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

    public function companyRegistration(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
    {

        $em = $this->getDoctrine()->getManager();
        $company = new Company;

        $company->setName($request->request->get('name'));
        $company->setAddress($request->request->get('address'));
        $company->setMail($request->request->get('email'));
        $company->setPhone($request->request->get('phone'));
        $company->setPassword($encoder->encodePassword($company, $request->request->get('password')));
        $company->setUsername($request->request->get('name'));

        $file = $request->files->get('picture');
        $pictureName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        
        $picture = $file->move(
            $this->getParameter('userPictures_directory'),
            $pictureName
        );

        $company->setPicture('img/users/pictures/'.$pictureName);
        
        // Génération d'une clé aléatoire pour l'activation du compte
        $company->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($company);
        $em->flush();

        $message = (new \Swift_Message('Corse-Connexion - Bienvenue'))
            ->setFrom('info@corse-connexion.corsica')
            ->setTo($company->getMail())
            ->setBody(
                $this->renderView(
                    'emails/companyRegistration.html.twig',[
                        'company' => $company
                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);

        $this->addFlash(
            'notice',
            'Merci de consulter ta boite de réception afin de confirmer ton adresse mail!'
        );

        $referer = $request
                ->headers
                ->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
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

        $user->setName($request->request->get('name'));
        $user->setAddress($request->request->get('address'));
        $user->setMail($request->request->get('email'));
        $user->setPhone($request->request->get('phone'));
        $user->setPicture($request->request->get('picture'));
        $user->setUsername($request->request->get('name'));
        
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

    /**
     * @Route("/companyValidEmail", name="companyValidEmail")
     */
    public function companyValidEmailAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        $em = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
        ->getManager()
        ->getRepository(Company::class)
        ->findOneById($_GET['userId']);
        
        $user->setValidEmail(true);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('homePro',
            [
                'user' => $user
            ]
        ); 
                Response::HTTP_OK
        ;
    }
}