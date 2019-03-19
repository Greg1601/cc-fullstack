<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Talent;
use App\Entity\Skill;

class TalentController extends AbstractController
{

    /**
     * @Route("/talents", name="TalentList")
     */
    public function talentListAction()
    {
        // récupération de tous les éléments de Talent pour affichage.
        $talents = $this->getDoctrine()
        ->getManager()
        ->getRepository(Talent::class)
        ->findBy([
            'isChecked' => '1'
        ]);

        shuffle($talents);
        
        // récupération de tous les élements Skill pour affichage si inscription
        $skills = $this->getDoctrine()
        ->getManager()
        ->getRepository(Skill::class)
        ->findAll();

        $user = $this->getUser();

        return $this->render('talents.html.twig', 
            [
                'talents' => $talents,
                'skills' => $skills,
                'user' => $user
            ]
        );
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
        $talent->setTitle($request->request->get('title'));
        $talent->setMail($request->request->get('email'));
        $talent->setPassword($encoder->encodePassword($talent, $request->request->get('password')));
        $talent->setLocation($request->request->get('location'));
        
        $username = $request->request->get('firstname').' '.$request->request->get('lastname');
        
        if ($request->files->get('avatar')) {
            $file = $request->files->get('avatar');
            $avatarName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $avatar = $file->move(
                $this->getParameter('userPictures_directory'),
                $avatarName
            );

            // $explodedPath = explode("corse-connexion/public/", $avatar);
            // dump('img/users/pictures/'.$avatarName);die;

            // $usablePath = $explodedPath[1];
            $talent->setAvatar('img/users/pictures/'.$avatarName);
        }
        else {
            $talent->setAvatar('https://robohash.org/'.$username.'.png');
        }

        if ($request->request->get('mobility')) {
            $talent->setIsMobile($request->request->get('mobility'));
        }
        else {
            $talent->setIsMobile(0);
        }

        if ($request->request->get('isFreelance')) {
            $talent->setIsFreelance($request->request->get('isFreelance'));
        }
        else {
            $talent->setIsFreelance(0);
        }

        if ($request->request->get('remoteOnly')) {
            $talent->setRemoteOnly($request->request->get('remoteOnly'));
        }
        else {
            $talent->setRemoteOnly(0);
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

        
        $file = $request->files->get('CV');
        if ($file) {
            $CVName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $cv = $file->move(
                $this->getParameter('userCV_directory'),
                $CVName
            );
            $talent->setCv('img/users/CV/'.$CVName);
        }
        
        // dump($cv);die;

        // $explodedPath = explode("corse-connexion/public/", $avatar);
        // dump('img/users/pictures/'.$avatarName);die;

        // $usablePath = $explodedPath[1];
    

        $talent->setUsername($username);

        // Génération d'une clé aléatoire pour l'activation du compte
        // $talent->setRandomKey($cle = md5(microtime(TRUE)*100000));
        $em->persist($talent);
        $em->flush();

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
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
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
        
        // dump($request);die;
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

        if ($request->request->get('remoteOnly')) {
            $user->setRemoteOnly($request->request->get('remoteOnly'));
        }
        else {
            $user->setRemoteOnly(0);
        }

        if ($request->request->get('isFreelance')) {
            $user->setIsFreelance($request->request->get('isFreelance'));
        }
        else {
            $user->setIsFreelance(0);
        }

        $skills = $request->request->get('skills');
        foreach ($skills as $s) {
            $skill = $this->getDoctrine()
            ->getManager()
            ->getRepository(Skill::class)
            ->findOneByName($s);
            $user->addSkill($skill);
        }
        
        if ($request->files->get('avatar')) {
            $file = $request->files->get('avatar');
            $avatarName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $avatar = $file->move(
                $this->getParameter('userPictures_directory'),
                $avatarName
            );

            // $explodedPath = explode("corse-connexion/public/", $avatar);
            // dump('img/users/pictures/'.$avatarName);die;

            // $usablePath = $explodedPath[1];
            $user->setAvatar('img/users/pictures/'.$avatarName);
        }

        if ($request->files->get('CV')) {
            $file = $request->files->get('CV');
            $CVName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $cv = $file->move(
                $this->getParameter('userCV_directory'),
                $CVName
            );
            // dump($cv);die;

            // $explodedPath = explode("corse-connexion/public/", $avatar);
            // dump('img/users/pictures/'.$avatarName);die;

            // $usablePath = $explodedPath[1];
            $user->setCv('img/users/CV/'.$CVName);
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