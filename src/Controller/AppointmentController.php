<?php

namespace App\Controller;
use App\Entity\Schedule;
use App\Form\ScheduleType;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ScheduleRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

use Symfony\Component\Serializer\Encoder\JsonEncode;



class AppointmentController extends AbstractController
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
	}

/**
 * @Route("/appointment", name="lc_appointment")
 */
	public function appointment(){

		
		return $this->render("appointment/appointment.html.twig");
	}

/**
 * @Route("/appointment/specialist", options={"expose"=true}, name="lc_appointment_specialist")
 */
	public function getAppointmentsBySpecialist()
	{

		$appointments = $this->getDoctrine()->getRepository('App:Schedule')->findFutureUnbookedEvents(new \DateTime());
		$data =  $this->serializer->serialize($appointments, 'json', ['groups'=>'appointment']);
	
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

	}

/**
 * @Route("/appointment/inscription/{id}", options={"expose"=true}, name="lc_appointment_inscription")
 */
	public function getInscriptionForm($id, Request $request, ScheduleRepository $scheduleRepository,  MailerInterface $mailer)
	{
		$schedule = $scheduleRepository->find($id);
		$form = $this->createForm(ScheduleType::class, $schedule);
		$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()){
    		$schedule = $form->getData();
    		$schedule->setIsBooked(true);
    		$this->entityManager->persist($schedule);
         	$this->entityManager->flush();

         	$client = $schedule->getClient();
         	$specialist = $schedule->getSpecialist();
         	$specialistName = $specialist->getName();
         	$message = "Спасибо! Ваша заявка принята, как только " . $specialistName . " подтвердит запись на консультацию, Вам будет выслано сообщение на указанный Вами email: " . $client->getEmail();
         	$this->addFlash(
            'notice',  
           	$message
        	);

			$email = (new TemplatedEmail())
			->from(new Address('govorysha@example.com', "Говоруша"))
			->to(new Address($schedule->getSpecialist()->getEmail(), $specialistName))
			->subject("Запись на консультацию: новая заявка")
			->htmlTemplate('emails/specialistAppointmentNotification.html.twig')
			->context(['client'=> $client, 'schedule'=>$schedule, 'specialist'=>$specialist]);
			$mailer->send($email);
        	return $this->redirectToRoute('lc_appointment');
    	}
		$form = $this->createForm(ScheduleType::class, $schedule);
		if ($request->isXmlHttpRequest()) {
			return $this->render('appointment/inscriptionForm.html.twig', [
        	'form' => $form->createView(),
   			]);
		}
		
	}
}