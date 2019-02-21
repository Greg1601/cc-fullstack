<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Talent;
use App\Entity\Skill;

class TalentController extends AbstractController
{

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

        if ($request->request->get('linkedIn')) {
            $talent->setLinkedin($request->request->get('linkedIn'));
        }
        else {
            $talent->setLinkedIn('Non renseigné');
        }
        
        $skills = $request->request->get('skills');
        foreach ($skills as $s) {
            $skill = $this->getDoctrine()
            ->getManager()
            ->getRepository(Skill::class)
            ->findOneByName($s);
            $talent->addSkill($skill);
        }

        $username = $request->request->get('firstname').' '.$request->request->get('lastname');
        $talent->setUsername($username);

        // Génération d'une clé aléatoire pour l'activation du compte
        // $talent->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($talent);
        $em->flush();

        return $this->redirectToRoute('homeTalent'); 
                Response::HTTP_OK
        ;
    }
    
    
    /**
     * @Route("/talentEdit", name="talentEdit")
     * @Method("POST")
     */
    public function talentEditAction(Request $request)
    {
        // récupération de tous les éléments de JobOffer pour affichage.
        // dump($request);die;
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        
        // dump($_POST);die;
        $user->setFirstname($request->request->get('firstname'));
        $user->setLastname($request->request->get('lastname'));
        $user->setMail($request->request->get('email'));
        $user->setLocation($request->request->get('location'));
        
        if ($request->request->get('linkedIn')) {
            $user->setLinkedin($request->request->get('linkedIn'));
        }
        else {
            $user->setLinkedIn('Non renseigné');
        }

        // $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
        if ($request->request->get('mobility')) {
            $user->setIsMobile($request->request->get('mobility'));
        }
        else {
            $user->setIsMobile(0);
        }

        $skills = $request->request->get('skills');
        foreach ($skills as $s) {
            $skill = $this->getDoctrine()
            ->getManager()
            ->getRepository(Skill::class)
            ->findOneByName($s);
            $user->addSkill($skill);
        }


        $username = $request->request->get('firstname').' '.$request->request->get('lastname');
        $user->setUsername($username);
        
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