<?php

//declare(strict_types=1);
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Posts;
use App\Entity\Rating;
use App\Entity\Vote;
use App\Form\PostFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PostController extends AbstractController
{
    /**
     * @Route("/create-post", name="create-post")
     */
    public function createPost(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();
        $formPost = $this->createForm(PostFormType::class, $post);
        


        $formPost->handleRequest($request);

        if($formPost->isSubmitted() && $formPost->isValid()) {


            $post->setUser($this->getUser());
            $post->setDate(new \DateTime("now", new \DateTimeZone("Europe/Amsterdam")));
            $post->setAmountOfRatings(0);
            $post->setTotalRatingScore(0);


            $entityManager->persist($post);

            $user = $this->getUser();
            $user?->setPostCount($user->getPostCount() + 1);


            $entityManager->flush();

            return $this->redirectToRoute('home');
        }


        return $this->render('post/create.html.twig',[

            "PostFormType" => $formPost->createView()] );

    }

                // FUNCTION 2 (DELETE)
    /**
     * @Route("/post/{id}/delete", name="delete-post")
     */
    public function deletePost(int $id, EntityManagerInterface $entityManager): Response
    {
        $post = $entityManager->getRepository(Posts::class)->find($id);

        $comments = $entityManager->getRepository(comment::class)->findBy(['post' => $post]);

        $user = $post->getUser();

        $post->getUser()->setPostCount($user->getPostCount() - 1);

//        dd($user);




        foreach ($comments as $comment) {
            $entityManager->remove($comment);
        }

        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }





            // FUNCTION 3 (EDIT)

    /**
     * @Route("edit-post/{id}", name="edit-post")
     */
    public function editPost(int $id, Request $request,EntityManagerInterface $entityManager) :Response
    {

        $post = $entityManager->getRepository(Posts::class)->find($id);
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
//        dd($post);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('show-posts');
        }



            return $this->render('post/create.html.twig', [
                "PostFormType" => $form->createView()
            ]);


        }




        // FUNCTION 4 (INSPECT) ALSO RETRIEVES COMMENTS !!!!

    /**
     * @Route("/inspect-post/{id}", name="inspect-post")
     */
    public function inspectPost($id,  EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $post = $entityManager->getRepository(Posts::class)->find($id);

        $comments = $entityManager->getRepository(Comment::class)->findBy(['post' => $post]);

        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);

        $votesByUser = $entityManager->getRepository(Vote::class)->findBy([
            'user' => $user,
            'comment' => $comments
        ]);


        return $this->render('post/inspect.html.twig', [
            'post' => $post,
            'userRatings' => $userRatings,
            'votesByUser' => $votesByUser

        ]);
    }




}





