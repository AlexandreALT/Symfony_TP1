<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function home(): Response
    {
        $user = $this->security->getUser();

        if (isset($_POST['newMessage'])) return $this->render('/home.html.twig', ['user_email' => $user->getUsername(), 'new_message' => $_POST['newMessage']]);
        else return $this->render('/home.html.twig', ['user_email' => $user->getUsername()]);
    }
}
