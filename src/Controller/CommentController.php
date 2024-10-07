<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Posts;
use App\Entity\User;
use App\Form\CommentFormType;


use App\Form\EditCommentFormType;
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
            $user?->setCommentCount($user->getCommentCount() + 1);

            $comment = new Comment();
            $comment->setCommentAuthor($user->getUsername());
            $comment->setUser($user);
            $comment->setCommentText($request->request->get('commentText'));
            $comment->setCreatedAt(new \DateTime("now", new \DateTimeZone("Europe/Amsterdam")));


            $comment->setPost($post);

            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inspect-post', ['id' => $id]);
    }




//show my comments function

    /**
     * @Route("show-comments", name="show-comments")
     */
    public function showMyComments(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $myComments = $entityManager->getRepository(Comment::class)->findBy(['user' => $user]);
//        dd($myComments);

        return $this->render('comments/show-comments.html.twig', [
            'myComments' => $myComments,
        ]);
    }


    /**
     * @Route("edit-comments/{id}", name="edit-comments")
     */
    public function editMyComments(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $comment = $entityManager->getRepository(Comment::class)->find($id);

        $form = $this->createForm(EditCommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show-comments');
        }

        return $this->render('comments/edit-comments.html.twig', [
            'editCommentForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("edit-post-comments/{id}", name="edit-post-comments")
     */

    public function editCommentFromPost(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $postComment = $entityManager->getRepository(Comment::class)->find($id);
        $postComment->setUpdatedAt(new \DateTime("now"));


        $form = $this->createForm(EditCommentFormType::class, $postComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($postComment);
            $entityManager->flush();

            $postId = $postComment->getPost()->getId();




            return $this->redirectToRoute('inspect-post', ['id' => $postId]);
        }

        return $this->render('comments/edit-comments.html.twig', [
            'editCommentForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("delete-comment/{id}", name="delete-comment")
     */
    public function deleteComment(int $id, EntityManagerInterface $entityManager): Response
    {
        $comment = $entityManager->getRepository(Comment::class)->find($id);

        $user = $this->getUser();

        $user->setCommentCount($user->getCommentCount() - 1);


        $postId = $comment->getPost()->getId();
        $entityManager->remove($comment);
        $entityManager->flush();



        return $this->redirectToRoute('inspect-post', ['id' => $postId]);

    }



    public function deleteCommentFromPost(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = $entityManager->getRepository(Comment::class)->find($id);

        $user = $this->getUser();

        $user->setCommentCount($user->getCommentCount() - 1);



        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('show-comments');
    }


}


