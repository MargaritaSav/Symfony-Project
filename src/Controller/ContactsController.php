<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Client;
use App\Form\ClientType;



class ContactsController extends AbstractController
{

/**
 * @Route("/contacts", options={"expose"=true}, name="lc_contact")
 */
	public function contact(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$contact = new Client();
		$form = $this->createForm(ClientType::class, $contact);
		$form->handleRequest($request);
		if ($request->isXmlHttpRequest() && $form->isValid()){
			$contact->setIsContactForm(true);
			$em->persist($contact);
			$em->flush();
			return new Response(json_encode(array('status'=>'success')));
		}
		return $this->render("contact/contact.html.twig",['form'=>$form->createView()]);
	}
}