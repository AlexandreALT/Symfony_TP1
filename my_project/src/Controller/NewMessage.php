<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\NewMessageType;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class NewMessage extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/new", name="new_message")
     */
    public function newMessage(Request $request): Response
    {
        $user = $this->security->getUser();

        $message = new Message();
        $form = $this->createForm(NewMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $message->setDate(new DateTime());
            $message->setUserId($user);

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/newMessage.html.twig', [
            'formNewMessage' => $form->createView()
        ]);
    }
}
