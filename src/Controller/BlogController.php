<?php

namespace App\Controller;

use App\Entity\Article;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog")
 */

class BlogController extends AbstractController{

	private $repository;
	private $em;
	public function __construct(ArticleRepository $repository){
		$this->repository = $repository;
		//$this->em = $em;
	}

/**
 * @Route("/{page}", name="lc_blog", requirements={"page" = "\d+"}, defaults={"page" = 1})
 */
	public function blog(){
		$article = $this->repository->findAll();
		$this->em->flush();
		return $this->render("blog/blog-layout.html.twig");
	}

	public function recentArticles()
    {
        // get the recent articles somehow (e.g. making a database query)
        $listAdverts = array(
      		array('id' => 1, 'title' => 'Recherche développeur Symfony'),
      		array('id' => 2, 'title' => 'Mission de webmaster'),
      		array('id' => 3, 'title' => 'Offre de stage webdesigner')
    	);

        return $this->render('Advert/_recent_articles.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

    /**
	* @Route ("/blog/{slugpath}-{id}", name="lc_article_view", requirements={"slugpath": "[a-z0-9\-]*"})
	*/
	public function article(Article $article, string $slugpath){
		if($article->getSlugPath()!==$slugpath){
			return $this->redirectToRoute('lc_article_view', [
				'id' => $article->getId(),
				'slugpath' => $article->getSlugPath()
			], 301);
		}

		//$article = $this->repository->find($id);
		return $this->render("blog/blog-article.html.twig", ["article" => $article]);
	}
}

// ajouter dans la base de données
/*$article = new Article();
		$article->setTitle("My first article")
		->setReview("My first article review")
		->setContent("My first article content");
		$em = $this->getDoctrine()->getManager();
		$em->persist($article);
		$em->flush();*/