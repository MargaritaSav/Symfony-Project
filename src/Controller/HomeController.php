<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class HomeController extends AbstractController
{
/**
 * @Route("/", name="lc_home")
 */
	public function index(ArticleRepository $repository)
	{
		$latestArticles = $repository->findLatest();
		return $this->render('homepage.html.twig', ['articles'=>$latestArticles]);
	}


}