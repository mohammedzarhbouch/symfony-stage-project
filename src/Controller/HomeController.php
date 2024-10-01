<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homepage(EntityManagerInterface $entityManager): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $allPosts = $entityManager->getRepository(Posts::class)->findAll();


        return $this->render('user/home.html.twig', [
            "posts" => $allPosts ]);

    }





    /**
     * @Route("/show-posts", name="show-posts")
     */
    public function myPosts(): Response
    {
        $user = $this->getUser();


        if ($user) {
            $myPosts = $user->getPosts();
        } else {
            $myPosts = [];
        }

        return $this->render('post/show.html.twig', [
            'myPosts' => $myPosts
        ]);
    }



}
