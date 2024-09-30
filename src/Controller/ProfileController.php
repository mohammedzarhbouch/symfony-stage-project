<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile/{id}", name="profilepage")
     */
    public function profilepage(int $id, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {


        $profile = $entityManager->getRepository(User::class)->find($id);
        $profileForm = $this->createForm(ProfileFormType::class, $profile);

        $profileForm->handleRequest($request);


        if ($profileForm->isSubmitted() && $profileForm->isValid()) {





            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $profileForm['imageFile']->getData();

            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/profile_image';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . time() . '.' . $uploadedFile->guessExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $profile->setImageFileName($newFilename);





            $user = $profileForm->getData();
            $plainPassword = $profileForm->get('plainPassword')->getData();

            if ($plainPassword) {

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
            }

            $entityManager->persist($profile);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }


        return $this->render('profile.html.twig', [
            'ProfileFormType' => $profileForm->createView(),
            'imageUrl' => '/uploads/profile_image/' . $profile->getImageFileName(),
            'profile' => $profile
        ]);

    }

}



