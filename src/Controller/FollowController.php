<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Follow;
use App\Entity\Posts;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowController extends AbstractController
{



    /**
     * @Route("/following", name="follow-list")
     */

    public function followedList(EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $userId = $user->getId();
//        dd($userId);
        $following = $entityManager->getRepository(Follow::class)->findBy(['followerUser' => $userId]);



//        dd($following);


        return $this->render('user/followList.html.twig', [
            'followedList' => $following
        ]);
    }









    /**
     * @Route("/post/follow/{id}", name="follow_user")
     */
    public function followUser(int $id, EntityManagerInterface $entityManager): Response
    {

        $currentUser = $this->getUser();



        $userToFollow = $entityManager->getRepository(User::class)->find($id);

        $existingFollow = $entityManager->getRepository(Follow::class)->findOneBy([
            'followerUser' => $currentUser,
            'followedUser' => $userToFollow
            ]);

        if(!$existingFollow) {

        $follow = new Follow();
        $follow->setFollowerUser($currentUser); // logged-in user right now
        $follow->setFollowedUser($userToFollow); // user that has been followed


        $entityManager->persist($follow);
        $entityManager->flush();
        return $this->redirectToRoute('home');
        }


        return $this->redirectToRoute('home', ['alreadyFollowing' => true]);
    }


    /**
     * @Route("/following/{id}", name="followedPosts")
     */
    public function goToFollowedPosts(int $id, EntityManagerInterface $entityManager): Response
    {


        $followedUser = $entityManager->getRepository(User::class)->find($id);
        if (!$followedUser) {
            throw $this->createNotFoundException('User not found with id:'. $id);
        }
        $posts = $entityManager->getRepository(Posts::class)->findBy(['user' => $followedUser]);

        if (empty($posts)) {
            return new Response('No posts found for user with ID: ' . $id);
        }




        return $this->render('post/followedUserPosts.html.twig', [
            'followingPosts' => $posts,

        ]);
    }



}
