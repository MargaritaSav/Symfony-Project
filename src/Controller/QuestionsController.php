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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\QuestionsRepository;
use App\Repository\SpecialistRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class QuestionsController extends AbstractController
{

/**
 * @Route("/questions", options={"expose"=true}, name="lc_faq")
 */
	public function questions(MailerInterface $mailer, Request $request, QuestionsRepository $qr)
	{
		$em = $this->getDoctrine()->getManager();
		$answeredQuestions = $qr->findBy(['isAnswered'=> true]);
		$question = new Questions;
		$form = $this->createForm(QuestionFormType::class, $question);
		$form->handleRequest($request);
		if ($request->isXmlHttpRequest() && $form->isValid()){
			$em->persist($question);
			$em->flush();
			$email = (new TemplatedEmail())
			->from(new Address('govorysha@example.com', "Говоруша"))
			->to(new Address('admin@example.com', $question->getAskedByName()))
			->subject("Новый вопрос от {$question->getAskedByName()}")
			->htmlTemplate('emails/question.html.twig')
			->context(['question'=> $question]);
			$mailer->send($email);
			return new Response(json_encode(array('status'=>'success')));
		}
		return $this->render("questions/questions.html.twig", ['form'=>$form->createView(), 'questions' => $answeredQuestions]);
	}

/**
 * @Route("questions/send-answer", name="send_answer")
 */
	public function sendAnswer(QuestionsRepository $qr, SpecialistRepository $sr, MailerInterface $mailer, Request $request)
	{
		
		$em = $this->getDoctrine()->getManager();
		$id = $request->query->get('id');
		$question = $qr->find($id);
		if(isset($_POST['answer'])){
			$specialist = $sr->find(intval($_POST['answeredBy']));
			$question->setAnswer($_POST['answer']);
			$question->setAnsweredBy($specialist);
			$em->persist($question);
		}
		if(!$question->getIsAnswerSent()&&$_POST['answer']!=null){
			$email = (new TemplatedEmail())
			->from(new Address('govorysha@example.com', "Говоруша"))
			->to(new Address($question->getAskedByEmail(), $question->getAskedByName()))
			->subject("Ответ на Ваш вопрос, заданный на сайте 'Логоцентр Говоруша'")
			->htmlTemplate('emails/answer.html.twig')
			->context(['question'=> $question]);
			$mailer->send($email);
			$question->setIsAnswerSent(true);
			$em->persist($question);
			$em->flush();
			$this->addFlash('info', 'Ваш ответ выслан пользователю на его email' );
			
		} elseif ($question->getIsAnswerSent()){
			$this->addFlash('info', 'Вы уже отправили Ваш ответ по email. Вы не можете отправить письмо ещё раз.' );
		} else{
			$this->addFlash('info', 'Вы не ввели ответ' );
		}
		

		$url = $this->generateUrl('easyadmin', 
		[
            'action' => 'edit',
            'entity' => $request->query->get('entity'),
            'id' => $id
        ], UrlGeneratorInterface::ABSOLUTE_URL);
		return new Response($url);
	}
}