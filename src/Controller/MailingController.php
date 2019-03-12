<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\MailingList;

class MailingController extends AbstractController
{
    /**
     * @Route("/getMail", name="getMail")
     */

    public function getMailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $mail = new MailingList;

        $mail->setMail($request->request->get('email'));
        $mail->setFirstname($request->request->get('firstname'));
        $mail->setLastname($request->request->get('lastname'));

        $em->persist($mail);
        $em->flush();

        $referer = $request
                ->headers
                ->get('referer');
        return $this->redirect($referer);
    }
    
}