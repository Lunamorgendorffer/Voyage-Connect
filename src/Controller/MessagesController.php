<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Entity\Notification;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    private $hub;

    public function __construct(HubInterface $hub)
    {
        $this->hub = $hub;
    }

    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {   
        // Récupérer les notifications de l'utilisateur connecté
        $notifications = $this->getUser()->getNotifications();

        return $this->render('messages/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/send', name: 'send')]
    public function send(EntityManagerInterface $entityManager, HubInterface $hub,Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());

            $message=$form->getData();
            $entityManager->persist($message);
            $entityManager->flush();

            $notification = new Notification();
            $notification->setNotifyMessage("Vous avez reçu un nouveau message.");
            $notification->setUser($message->getRecipient());
            $notification->setRelatedMessage($message);

            $entityManager->persist($notification);
            $entityManager->flush();

            //Publication de la notification à User2 via Mercure
            $update = new Update(
                'https://example.com/books/1', // Remplacez par l'URL de la ressource pertinente pour la notification
                json_encode(['status' => $notification->getNotifyMessage()])
            );
    
            $this->hub->publish($update);

            // $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView(),
            // 'messages' => $messages->getRepository(Messages::class)->findAll(),
        ]);
    }

    #[Route('/received', name: 'received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    #[Route('/sent', name: 'sent')]
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }


    #[Route('/publish', name: 'publish')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['status' => 'message received'])
        );

        $hub->publish($update);

        return new Response('published!');
    }

    #[Route('/read/{id}', name: 'read')]
    public function read(EntityManagerInterface $entityManager, Messages $message): Response
    {
        $message->setIsRead(true);
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

    #[Route('/message/delete/{id}', name: 'delete_message')]
    public function delete(EntityManagerInterface $entityManager, Messages $message): Response
    {
    
        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute("received");
    }

}
