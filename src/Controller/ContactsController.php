<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ContactsController extends AbstractController{

/**
 * @Route("/contcats", name="lc_contact")
 */
	public function contact(){
		return $this->render("contact/contact.html.twig");
	}
}