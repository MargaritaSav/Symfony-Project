<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Questions;
use App\Form\QuestionFormType;



class QuestionsController extends AbstractController{

/**
 * @Route("/questions", options={"expose"=true}, name="lc_faq")
 */
	public function questions(Request $request){
		$em=$this->getDoctrine()->getManager();
		$question = new Questions;
		$form = $this->createForm(QuestionFormType::class, $question);
		$form->handleRequest($request);
		if ($request->isXmlHttpRequest()  && $form->isValid()){
			$em->persist($question);
			$em->flush();
			return new Response(json_encode(array('status'=>'success')));
		}
		return $this->render("questions/questions.html.twig", ['form'=>$form->createView()]);
	}
}