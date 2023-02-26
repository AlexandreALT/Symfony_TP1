<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Message;
use App\Entity\User;

class Home extends AbstractController
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
     * @Route("/home", name="app_home")
     */
    public function home(ManagerRegistry $doctrine): Response
    {
        $user = $this->security->getUser();

        if(isset($_POST['deleteMessage'])) {
            $em = $this->getDoctrine()->getManager();

            $deleteMessage = $doctrine->getRepository(Message::class)->find($_POST['deleteMessage']);
            $em->remove($deleteMessage);
            $em->flush();
        }

        if (isset($_POST['incrementOffset'])) $offset = $_POST['incrementOffset'] + 5;
        else if (isset($_POST['decrementOffset']) && $_POST['decrementOffset'] - 5 >= 0) $offset = $_POST['decrementOffset'] - 5;
        else $offset = 0;

        $messages = $doctrine->getRepository(Message::class)->findBy(array(), array('date' => 'DESC'), 5, $offset);

        $tab = array();

        foreach ($messages as $message) {
            $tabMessage = array();

            $id = $message->getId();
            $content = $message->getContent();
            $date = date("d/m/Y", date_timestamp_get($message->getDate()));
            $userMessage = $doctrine->getRepository(User::class)->find($message->getUserId());

            array_push($tabMessage, array('id' => $id, 'content' => $content, 'date' => $date, 'user_mail' => $userMessage->getEmail()));
            array_push($tab, $tabMessage);
        }

        return $this->render('/home.html.twig', ['user_email' => $user->getUsername(), 'messages' => $tab, 'offset' => $offset]);
    }
}
