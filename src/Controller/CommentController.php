<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Posts;
use App\Form\CommentFormType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     * @Route("/post/{id}/comment", name="postComment", methods={"POST"})
     */
    public function postComment($id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $post = $entityManager->getRepository(Posts::class)->find($id);

        if($post) {

            $user = $this->getUser();

            $comment = new Comment();
            $comment->setCommentAuthor($user->getUsername());
            $comment->setCommentText($request->request->get('commentText'));
            $comment->setCreatedAt(new \DateTime("now", new \DateTimeZone("Europe/Amsterdam")));


            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inspect-post', ['id' => $id]);
    }


}


