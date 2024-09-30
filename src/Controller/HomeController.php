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

        $user = $this->getUser();
        $allPosts = $entityManager->getRepository(Posts::class)->findAll();
//        dd($testPost);

        return $this->render('home/home.html.twig', [

            "posts" => $allPosts ]);
    }





    /**
     * @Route("/show-posts", name="show-posts")
     */
    public function myPosts(EntityManagerInterface $entityManager): Response
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
