<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Entity\Talent;
use App\Entity\JobOffer;
use App\Entity\Skill;

class AppFixtures extends Fixture
{
    private $encoder;
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $this->encoder = $encoder;
        $this->manager = $manager;
    }

    // public function load(ObjectManager $manager)
    // {
    //     for ($i = 0; $i < 20; $i++) {
            
    //         // creation de 20 objets Talent
    //         $talent = new Talent();
    //         $username = 'lastname'.$i.' firstname'.$i;
    //         $talent->setLastname('lastname'.$i);
    //         $talent->setFirstname('lastname'.$i);
    //         $talent->setMail('lastname'.$i.'.'.'firstname'.$i.'@gmail.com');
    //         $talent->setLocation('lastname'.$i.'place');
    //         $talent->setAvatar('https://robohash.org/'.$username.'.png');
    //         $talent->setPassword($this->encoder->encodePassword($talent, ('test')));
    //         $talent->setIsMobile(mt_rand(0, 1));
    //         $talent->setRemoteOnly(mt_rand(0, 1));
    //         $talent->setIsFreelance(mt_rand(0, 1));
    //         $talent->setTitle('Développeur fullstack php/symfony');

    //         $skills = $this->manager
    //             ->getRepository(Skill::class)
    //             ->findAll()
    //         ;
    //         $skillCount = count($skills);
    //         $randomized = mt_rand(3, $skillCount -2);
    //         $randomSkills = [[$randomized], [$randomized+1], [$randomized+2]];

    //         foreach ($randomSkills as $r) {
    //             $skill = $this->manager
    //             ->getRepository(Skill::class)
    //             ->findOneById($r);
    //             // dump($skill);die;
    //             $talent->addSkill($skill);
    //         };

    //         $talent->setUsername('firstname'.$i.' lastname'.$i);
    //         $talent->setLinkedIn('linkedIn'.$i);
    //         $manager->persist($talent);


    //         //Création de 20 objets Company
    //         $company = new Company();
    //         $username = 'company'.$i;
    //         $company->setName('company'.$i);
    //         $company->setPicture('img\team\qwant_music_s5bqg8.jpg');
    //         $company->setMail('company'.$i.'@gmail.com');
    //         $company->setAddress('adresse de l\'entreprise company'.$i);
    //         $company->setPhone('company');
    //         $company->setPassword($this->encoder->encodePassword($talent, ('test')));
    //         $company->setUsername($username);
    //         $manager->persist($company);
    //     }
        
    //     $manager->flush();
    // }
    
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            //création de 50 objets jobOffer
            $job = new JobOffer();
            $job->setJobName('Offre d\emploi'.$i);
            $job->setJobDescription('Description de l\'offre d\'emploi'.$i);
            $job->setRequiredExperience(mt_rand(0, 6).' ans');
            $job->setSalary(mt_rand(1800, 4000).'€');
            $job->setJobPlace('place'.$i);
            $job->setContact('contact job'.$i.'@gmail.com');
            $job->setRemotePossibility(mt_rand(0, 1));

            $companies = $this->manager
                ->getRepository(Company::class)
                ->findAll()
            ;
            $companyCount = count($companies);
            $randomized = mt_rand(66, 85);
            
            $company = $this->manager
                ->getRepository(Company::class)
                ->findOneById($randomized);
            
            // dump($company);die;
            $job->setCompany($company);
            $job->setIsChecked(0);

            $manager->persist($job);
        }

        $manager->flush();
        
    }
}
