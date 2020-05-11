<?php

namespace App\Controller\EasyAdmin;


use App\Entity\Schedule;
use App\Entity\Specialist;
use App\Form\SpecialistType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

use Symfony\Component\Serializer\Encoder\JsonEncode;




class AgendaController extends AbstractController
{
	private $serializer;
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		//dump(new JsonEncode(['JSON_UNESCAPED_UNICODE'])); die;
		$encoders = [new JsonEncoder(new JsonEncode(['json_encode_options'=>JSON_UNESCAPED_UNICODE]))];
		$defaultContext = [
    		AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
        		return $object->getId();
    		}
		];
		$classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

		$normalizer = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
		$normalizers = [new DateTimeNormalizer(), $normalizer];
		
		$this->serializer = new Serializer($normalizers, $encoders);
		$this->entityManager = $entityManager;
	}//2020-04-29T10:00:00+00:00

/**
 * @Route("logoped-manager/agenda", options={"expose"=true}, name="lc_agenda", methods={"GET"})
 * 
 */
	public function getSchedule()
	{
		$appointments = $this->getDoctrine()->getRepository('App:Schedule')->findAll();
		$data =  $this->serializer->serialize($appointments, 'json', ['groups'=>'schedule']);
	
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
        
	}

/**
 * @Route("/agenda/form", options={"expose"=true}, name="lc_agenda_form", methods={"GET"})
 * 
 */
	public function getForm(Request $request)
	{
		

		$schedule = new Schedule();
		
	
		$form = $this->createForm(SpecialistType::class, $schedule);
		if ($request->isXmlHttpRequest()){
        	return $this->render('admin/form.html.twig', [
            'form' => $form->createView()]);
    	}
        
	}

/**
 * @Route("logoped-manager/agenda/delete", options={"expose"=true}, name="lc_agenda_delete", methods={"POST"})
 * 
*/
	public function deleteEvent(ScheduleRepository $scheduleRepository)
	{
		if (isset($_POST["eventId"])) {
			$id = $_POST["eventId"];
			$schedule = $scheduleRepository->find($id);
			$message = 'Событие ' . $schedule->getTitle() . ' удалено из Вашего расписания';	
			$this->entityManager->remove($schedule);
			$this->entityManager->flush();

			$this->addFlash('success', message);
		}
		return $this->redirectToRoute('easyadmin', ['entity'=>'Schedule']);

	}

/**
 * @Route("logoped-manager/agenda/confirm/{mailId}/{fromEmail}", options={"expose"=true}, name="lc_agenda_confirm", methods={"POST", "GET"}, defaults={"mailId" = 0, "fromEmail" = false})
 * 
*/
	public function confirmEvent(ScheduleRepository $scheduleRepository, MailerInterface $mailer, $mailId, $fromEmail)
	{

		if (isset($_POST["eventId"]) && !$fromEmail) {
			$id = $_POST["eventId"];
		} elseif ($mailId!=0 && $fromEmail) {
			$id = $mailId;
		}
		$emailSubject = "Подтверждение записи на консультацию в логоцентр 'Говоруша'";
		$emailTemplatePath = 'emails/confirmAppointment.html.twig';
		$this->manageEvent($scheduleRepository, "confirm", $id, $emailSubject, $emailTemplatePath, $mailer);
		$schedule = $scheduleRepository->find($id);
		$client = $schedule->getClient();

		$message = "Запись на приём '" . $schedule->getTitle() ."' подтверждена. " . $client->getName() ." получит уведомление по электронной почте";
			
		$this->addFlash('success', $message);	
			
		return $this->redirectToRoute('easyadmin', ['entity'=>'Schedule']);
			
	}

/**
 * @Route("logoped-manager/agenda/cancel/{mailId}/{fromEmail}", options={"expose"=true}, name="lc_agenda_cancel", methods={"POST", "GET"}, defaults={"mailId" = 0, "fromEmail" = false})
 * 
*/

	public function cancelEvent(ScheduleRepository $scheduleRepository, MailerInterface $mailer, $mailId, $fromEmail)
	{
		if (isset($_POST["eventId"]) && !$fromEmail) {
			$id = $_POST["eventId"];
		} elseif ($mailId!=0 && $fromEmail) {
			$id = $mailId;
		}

		$schedule = $scheduleRepository->find($id);
		$client = $schedule->getClient();
		$emailSubject = "Отмена записи на консультацию в логоцентр 'Говоруша'";
		$emailTemplatePath = 'emails/cancelAppointment.html.twig';
		$this->manageEvent($scheduleRepository, "cancel", $id, $emailSubject, $emailTemplatePath, $mailer);
		
		$message = "Запись на приём '" . $schedule->getTitle() ."' отменена. " . $client->getName() . " получит уведомление по электронной почте";
			
		$this->addFlash('success', $message);	
		
		return $this->redirectToRoute('easyadmin', ['entity'=>'Schedule']);

	}

	private function manageEvent(ScheduleRepository $scheduleRepository, string $eventAction, int $eventId, string $emailSubject, string $emailTemplatePath,  MailerInterface $mailer)
	{
		$schedule = $scheduleRepository->find($eventId);
		$client = $schedule->getClient();
		$specialist = $schedule->getSpecialist();


		$email = (new TemplatedEmail())
			->from(new Address('govorysha@example.com', "Говоруша"))
			->to(new Address($client->getEmail(), $client->getName()))
			->subject($emailSubject)
			->htmlTemplate($emailTemplatePath)
			->context(['client'=> $client, 'schedule'=>$schedule, 'specialist'=>$specialist]);
			$mailer->send($email);
			if ($eventAction == 'cancel') {
				$schedule->setIsConfirmed(false);
				$schedule->setIsBooked(false);
				$schedule->setClient(null);
			} elseif($eventAction == 'confirm'){
				$schedule->setIsConfirmed(true);
			}
			
			$this->entityManager->persist($schedule);
			$this->entityManager->flush();
	}

}