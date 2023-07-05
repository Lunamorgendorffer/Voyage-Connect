<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'app_messages')]
    public function index(): Response
    {
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
        ]);
    }

    #[Route('/send', name: 'send')]
    public function send(EntityManagerInterface $entityManager, Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());

            $message=$form->getData();
            $entityManager->persist($message);
            $entityManager->flush();

            // $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("app_messages");
        }

        return $this->render("messages/send.html.twig", [
            "form" => $form->createView()
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
