<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Admin;

class AdminController extends AbstractController
{

    /**
     * @Route("/registerAdmin", name="registerAdmin")
     * @Method("POST")
     */

    public function registerAdmin(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $username = $request->request->get('firstname').' '.$request->request->get('lastname');

        $em = $this->getDoctrine()->getManager();
        $admin = new Admin;
        $admin->setLastname($request->request->get('lastname'));
        $admin->setFirstname($request->request->get('firstname'));
        $admin->setMail($request->request->get('email'));
        $admin->setPassword($encoder->encodePassword($admin, $request->request->get('password')));
        $admin->setUsername($username);
        
        $em->persist($admin);
        $em->flush();

        return $this->redirectToRoute('homePro'); 
                Response::HTTP_OK
        ;
    }

    /**
     * @Route("/adminEdit", name="adminEdit")
     * @Method("POST")
     */
    public function adminEditAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $user->setMail($request->request->get('email'));
        
        $username = $request->request->get('firstname').' '.$request->request->get('lastname');
        $user->setUsername($username);
        
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