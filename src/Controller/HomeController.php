<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Rating;
use App\Form\PostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homepage(Request $request,EntityManagerInterface $entityManager): Response
    {
//    dd($request->get('alreadyFollowing'));

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $allPosts = $entityManager->getRepository(Posts::class)->findAll();


        $user = $this->getUser();
        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);



            return $this->render('user/home.html.twig', [
                "posts" => $allPosts,
                'alreadyFollowing' => $request->get('alreadyFollowing'),
                'userRatings' => $userRatings,

            ]);

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





    /**
     * @Route("/search-posts", name="searched-posts", methods={"POST"})
     */
    public function searchedPosts(Request $request, EntityManagerInterface $entityManager): Response
    {

        $data = json_decode($request->getContent(), true);
        $searchInput = $data['searchInput'];
        $searchedPosts = [];
        $userRatingsArray = [];


        $user = $this->getUser();
        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);

        if ($searchInput) {
            $searchedThing = $entityManager->getRepository(Posts::class)
                ->searchByTitleAndText($searchInput);

        }
        foreach($searchedThing as $post){
            $searchedPosts[] = $post->toArray();

        }


        foreach($userRatings as $rating){
            $userRatingsArray[] = $rating->toArray();
        }

        return $this->json([
            'posts' => $searchedPosts,
            'alreadyFollowing' => $request->get('alreadyFollowing'),
            'userRatings' => $userRatingsArray,
        ]);
    }

}
