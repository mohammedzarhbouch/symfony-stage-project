<?php

//declare(strict_types=1);
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Follow;
use App\Entity\Posts;
use App\Entity\Rating;
use App\Entity\View;
use App\Entity\Vote;
use App\Entity\Like;

use App\Form\PostFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PostController extends AbstractController
{

    /**
     * @Route("/post-page", name="post-page")
     */

    public function postpage( Request $request, EntityManagerInterface $entityManager): Response
    {

        $allPosts = $entityManager->getRepository(Posts::class)->findAll();

        $alreadyFollowing = $request->get('alreadyFollowing');

        $user = $this->getUser();
        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);

        $likes = $entityManager->getRepository(Like::class)->findBy(['user' => $user]);
//        dd($likes);


        return $this->render('post/postpage.html.twig', [
            'allPosts' => $allPosts,
            'user' => $user,
            'userRatings' => $userRatings,
            'likes' => $likes,
            'alreadyFollowing' => $alreadyFollowing,



        ]);
    }









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



    public function addView(int $postId, EntityManagerInterface $entityManager, $user): void
    {
        $post = $entityManager->getRepository(Posts::class)->find($postId);
        $alreadyViewed = $entityManager->getRepository(View::class)->findOneBy([
            'post' => $post,
            'user' => $user
        ]);

        if (!$alreadyViewed) {
            $viewed = new View();
            $viewed->setPost($post);
            $viewed->setUser($user);
            $viewed->setCreatedAt(new \DateTime("now", new \DateTimeZone("Europe/Amsterdam")));

            //adds 1 to total views
            $oldTotalViews = $post->getTotalViews();
            $post->setTotalViews($oldTotalViews + 1);

            $entityManager->persist($post);
            $entityManager->persist($viewed);
            $entityManager->flush();
        }
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

        $likes = $entityManager->getRepository(Like::class)->findBy(['user' => $user]);

        // add view function
        $this->addView($id, $entityManager, $user);
        $totalViews = $post->getTotalViews();




        return $this->render('post/inspect.html.twig', [
            'post' => $post,
            'userRatings' => $userRatings,
            'votesByUser' => $votesByUser,
            'likes' => $likes,
            'totalViews' => $totalViews,

        ]);
    }


    /**
     * @Route("/like-post/{id}", name="like-post", methods={"POST"})
     */

    public function likePost(int $id, EntityManagerInterface $entityManager): Response

    {
        $user = $this->getUser();
        $post = $entityManager->getRepository(Posts::class)->find($id);



        $alreadyLiked = $entityManager->getRepository(Like::class)->findOneBy([
            'post' => $post,
            'user' => $user,
            ]);




        if ($alreadyLiked) {

            $entityManager->remove($alreadyLiked);
            $currentTotalLikes = $post->getTotalLikes();
            $newTotalLikes = $currentTotalLikes - 1;
            $post->setTotalLikes($newTotalLikes);





        }else{

            $like = new Like();
            $like->setUser($this->getUser());
            $like->setPost($post);
            $like->setLiked(true);

            $entityManager->persist($like);


            $currentTotalLikes = $post->getTotalLikes();
            $newTotalLikes = $currentTotalLikes + 1;
            $post->setTotalLikes($newTotalLikes);


        }




        $entityManager->flush();

        return $this->json([
            'success' => true,
            'newTotalLikes' => $newTotalLikes,

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
            $searchedThing = $entityManager->getRepository(Posts::class)->searchByTitleAndText($searchInput);

        }else{
            $searchedThing = $entityManager->getRepository(Posts::class)->findAll();
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




    /**
     * @Route("/most-liked", name="most-liked", methods={"GET"})
     */
    // filter button
    public function mostLiked(EntityManagerInterface $entityManager): Response
    {


        $user = $this->getUser();

        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);
        $mostLikedPosts = $entityManager->getRepository(Posts::class)->findMostLikedPosts(10);

        $userRatingsArray = [];

        foreach($userRatings as $rating){
            $userRatingsArray[] = $rating->toArray();
        }


        $mostLikedPostsArray = [];

        foreach($mostLikedPosts as $post){
            $mostLikedPostsArray[] = $post->toArray();
        }



        return $this->json([
            'mostLikedPosts' => $mostLikedPostsArray,
            'userRatings' => $userRatingsArray,

        ]);
    }



    /**
     * @Route ("/following-posts", name="following-posts")
     */
    // filter button
    public function followingPosts(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $userRatings = $entityManager->getRepository(Rating::class)->findBy(['user' => $user]);

        $following = $entityManager->getRepository(Follow::class)->findBy(['followerUser' => $user]);


        $followedUserIds = [];

        foreach($following as $follow){
            $followedUserIds[] = $follow->getFollowedUser()->getId();
        }


        $userRatingsArray = [];

        foreach($userRatings as $rating){
            $userRatingsArray[] = $rating->toArray();
        }



        $postsFromFollowing = $entityManager->getRepository(Posts::class)->findBy(['user' => $followedUserIds]);



        $postsArray = array_map(function ($post) {
            return $post->toArray();
        }, $postsFromFollowing);


        return $this->json([
            'followedPosts' => $postsArray,
            'userRatings' => $userRatingsArray,

        ]);
    }


}





