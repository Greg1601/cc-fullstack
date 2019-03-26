<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ContactMsg;

class ContactMsgController extends AbstractController
{
    /**
     * @Route("/getMessage", name="getMessage")
     */

    public function getMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = new ContactMsg;

        // dump($_POST);die;
        $contact->setType($request->request->get('contactType'));
        $contact->setMail($request->request->get('email'));
        $contact->setName($request->request->get('name'));
        $contact->setMessage($request->request->get('message'));
        $contact->setPhone($request->request->get('phone'));

        $em->persist($contact);
        $em->flush();

        $referer = $request
                ->headers
                ->get('referer');
        return $this->redirect($referer);
    }
    
}