<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/blog")
 */

class BlogController extends AbstractController
{

	private $articleRepository;
	private $categoryRepository;
	private $em;
	private $itemsPerPage;

	public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
	{
		$this->articleRepository = $articleRepository;
		$this->categoryRepository = $categoryRepository;
		$this->itemsPerPage = 3;
		//$this->em = $em;
	}

/**
 * @Route("/", name="lc_blog")
 */
	public function blog(Request $request, PaginatorInterface $paginator)
	{
		$data = $this->articleRepository->findBy([],['id' => 'DESC']);
		$articles = $paginator->paginate(
			$data,
			$request->query->getInt('page', 1), $this->itemsPerPage
		);
		$categories = $this->categoryRepository->findAll();
		
		if ($request->isXmlHttpRequest()){
			return $this->render("blog/list-articles.html.twig", ["articles" => $articles] );
		}

		return $this->render("blog/blog-layout.html.twig", ["articles" => $articles, "categories" => $categories, 'isAjax' => false] );
	}

	/**
	* @Route ("/{id}", name="lc_blog_category", requirements={"category": "[0-9]*"})
	*/

	public function blogByCategory(Category $category, Request $request, PaginatorInterface $paginator)
	{
		
		$data = $category->getArticles();
		$articles = $paginator->paginate(
			$data,
			$request->query->getInt('page', 1), 
			$this->itemsPerPage
		);
		return $this->render("blog/list-articles.html.twig", ["articles" => $articles] );
	}


    /**
	* @Route ("/{slugpath}/{id}", name="lc_article_view", requirements={"slugpath": "[a-z0-9\-]*"})
	*/
	public function article(Article $article, string $slugpath)
	{
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
