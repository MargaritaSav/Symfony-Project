<?php

namespace App\DataFixtures;
use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
	private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
    	
    	$admin2 = new Admin();
    	$admin2->setName('Vania');
    	$admin2->setEmail('example2@example.com');
    	$admin2->setRoles(['ROLE_ADMIN']);
    	

    	

    	$admin2->setPassword($this->passwordEncoder->encodePassword(
    		$admin2,
    		'password'
    	));
        
        
        $manager->persist($admin2);

       $manager->flush();
    }
}
