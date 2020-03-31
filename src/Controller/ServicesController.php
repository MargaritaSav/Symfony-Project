<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/services")
 */
class ServicesController extends AbstractController{

/**
 * @Route(" ", name="lc_services")
 */
	public function sevices(){
		return $this->render('services/particular.html.twig', array('title'=>"Наши услуги"));
	}

/**
 * @Route("/particular", name="lc_particular")
 */
	public function particular(){
		return $this->render('services/particular.html.twig', array('title'=>"Индивидуальные занятия"));

	}

/**
 * @Route("/group", name="lc_group")
 */
	public function group(){
	return $this->render('services/group.html.twig', array('title'=>"Групповые занятия для детей"));
	}
}