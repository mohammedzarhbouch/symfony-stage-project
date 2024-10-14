<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Rating;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{

    /**
     * @Route("/rating/{id}", name="rate-post", methods={"POST"})
     */
    public function ratePost( Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        // get logged-in user
        $user = $this->getUser();

        // Get the rating from the request
        $data = json_decode($request->getContent(), true);
        $ratingValue = $data['rating']; // Ensure this matches the key used in the fetch body

        $alreadyRated = $entityManager->getRepository(Rating::class)->findOneBy([
            'post' => $post,
            'user' => $user
        ]);


        if ($alreadyRated) {

            // get the old rating from the score column in the rating table UPDATE it
            $oldRatingValue = $alreadyRated->getScore();
            $alreadyRated->setScore(intval($ratingValue));

            // get the current total score from the total_rating_score column in the db
            $currentTotalScore = $post->getTotalRatingScore();

            // minus the old score plus the new score
            $newTotalScore = $currentTotalScore - $oldRatingValue + intval($ratingValue);

            // Set the new total rating score
            $post->setTotalRatingScore($newTotalScore);

            // Persist the updated rating and post
            $entityManager->persist($alreadyRated);
            $entityManager->persist($post);

        }else {



            // take the rating and set it in the db
            $newRating = new Rating();
            $newRating->setPost($post);
            $newRating->setUser($user);
            $newRating->setScore(intval($ratingValue));

            // get the total score from the total_rating_score column in the db
            $currentTotalScore = $post->getTotalRatingScore();

            //retrieve the amount_of_ratings from the post table
            // add 1 to it and set it back to the post table
            $currentAmountOfRating = $post->getAmountOfRatings();
            $newAmountOfRating = $currentAmountOfRating + 1;
            $post->setAmountOfRatings($newAmountOfRating);


            // retrieves the total_rating_score and add the $ratingValue to it (the data value of the button that was pressed)
            $newTotalScore = $currentTotalScore + intval($ratingValue);

            // set the total_rating_score column to the $newTotalScore
            $post->setTotalRatingScore($newTotalScore);


            $entityManager->persist($newRating);


        }


        $entityManager->flush();
        $newAverageRating = $post->AverageRating();
        return $this->json([
            'message' => 'Rating successfully saved',

            'newAverageRating' => $newAverageRating
         ]);
    }







}
