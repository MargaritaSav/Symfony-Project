<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class FeedbackController extends AbstractController{

/**
 * @Route("/feedback", name="lc_feedback")
 */
	public function feedback(){
		return new Response("<h1>Ca marche</h1>");
	}
}