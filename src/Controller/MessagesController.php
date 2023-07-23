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

    // Affiche la boîte de réception des messages
    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {   
        // Récupère les notifications de l'utilisateur courant
        $notifications = $this->getUser()->getNotifications();

        return $this->render('messages/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    // Affiche le formulaire pour envoyer un nouveau message
    #[Route('/send', name: 'send')]
    public function send(EntityManagerInterface $entityManager, HubInterface $hub, Request $request): Response
    {
        // Crée un nouvel objet "Messages"
        $message = new Messages();

        // Crée le formulaire lié à l'objet "Messages"
        $form = $this->createForm(MessagesType::class, $message);
        
        // Traite la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Lie l'expéditeur au message (l'utilisateur courant)
            $message->setSender($this->getUser());

            // Récupère les données du formulaire
            $message = $form->getData();

            // Persiste le message dans la base de données
            $entityManager->persist($message);
            $entityManager->flush();

            // Crée une nouvelle notification pour l'utilisateur destinataire
            $notification = new Notification();
            $notification->setNotifyMessage("Vous avez reçu un nouveau message.");
            $notification->setUser($message->getRecipient());
            $notification->setRelatedMessage($message);

            // Persiste la notification dans la base de données
            $entityManager->persist($notification);
            $entityManager->flush();

            // Envoie une mise à jour au serveur Mercure pour informer le destinataire
            $update = new Update(//Crée un nouvel objet Update, qui représente une mise à jour à publier via le protocole Mercure.
                'https://example.com/books/1', //C'est l'URI (Uniform Resource Identifier) de la ressource concernée par la mise à jour. 
                // prepare les données à envoyer dans la mise à jour en les encodant au format JSON. 
                json_encode(['status' => $notification->getNotifyMessage()]) // tableau associatif avec une clé "status" dont la valeur est le message de notification obtenu à partir de $notification->getNotifyMessage().
            );
            $this->hub->publish($update);//méthode publish du service HubInterface est appelée pour publier la mise à jour. 

            // Redirige vers la boîte de réception des messages
            return $this->redirectToRoute("app_messages");
        }

        // Affiche le formulaire d'envoi de message
        return $this->render("messages/send.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    // Affiche la boîte de réception
    #[Route('/received', name: 'received')]
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }

    // Affiche les messages envoyés
    #[Route('/sent', name: 'sent')]
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }

    // Publie un message 
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

    // Marque un message comme lu
    #[Route('/read/{id}', name: 'read')]
    public function read(EntityManagerInterface $entityManager, Messages $message): Response
    {
        $message->setIsRead(true);
        $entityManager->persist($message);
        $entityManager->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }

    // Supprime un message
    #[Route('/message/delete/{id}', name: 'delete_message')]
    public function delete(EntityManagerInterface $entityManager, Messages $message): Response
    {
        $entityManager->remove($message);
        $entityManager->flush();

        // Redirige vers la boîte de réception des messages
        return $this->redirectToRoute("received");
    }

}
