<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class QuestionsController extends AbstractController{

/**
 * @Route("/questions", name="lc_faq")
 */
	public function questions(){
		return $this->render("questions/questions.html.twig");
	}
}