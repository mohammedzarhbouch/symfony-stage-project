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
//        $userId = $user->getId();
        $following = $entityManager->getRepository(Follow::class)->findBy(['followerUser' => $user]);


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
        return $this->redirectToRoute('follow-list');
        } else {
            $entityManager->remove($existingFollow);
            $entityManager->flush();
        }


        return $this->redirectToRoute('follow-list', ['alreadyFollowing' => true]);
    }


    /**
     * @Route("/following/{id}", name="followedPosts")
     */
    public function goToFollowedPosts(int $id, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $followedUser = $entityManager->getRepository(User::class)->find($id);



        if (!$followedUser) {
            throw $this->createNotFoundException('User not found with id:'. $id);
        }
        $posts = $entityManager->getRepository(Posts::class)->findBy(['user' => $followedUser]);

        if (empty($posts)) {
            return new Response('No posts found for user with ID: ' . $id);
        }

        // adds all the likes the logged-in user has received and saves it in the variable.
        $totalLikes = array_reduce($posts, function ($sum, $post) {
            return $sum + $post->getTotalLikes();
        }, 0);
//        dd($totalLikes);

        $totalViews = array_reduce($posts, function ($sum, $post) {
            return $sum + $post->getTotalViews();
        });


        $postCount = $followedUser->getPostCount();



        return $this->render('post/followedUserPosts.html.twig', [
            'followingPosts' => $posts,
            'followedUser' => $followedUser,
            'totalLikes' => $totalLikes,
            'totalViews' => $totalViews,
            'user' => $user,
            'postCount' => $postCount,

        ]);
    }



}
