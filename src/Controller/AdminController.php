<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Posts;
use App\Entity\User;
use App\Form\AdminPostEditFormType;
use App\Form\EditUserFormType;
use App\Form\PostFormType;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function adminDashboard(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {

            $view = $request->query->get('view', 'users');


            if ($view === 'posts') {
                $posts = $entityManager->getRepository(Posts::class)->findAll();
                return $this->render('admin/admin.html.twig', [
                    'posts' => $posts,
                ]);
            }


            $users = $entityManager->getRepository(User::class)->findAll();
            return $this->render('admin/admin.html.twig', [
                'users' => $users,
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("/user/delete/{id}", name="admin_delete_user", methods={"post"})
     */
    public function deleteUserRow(EntityManagerInterface $entityManager, User $user): Response

    {
        $userRow = $user;
        $posts = $entityManager->getRepository(Posts::class)->findBy(['user' => $userRow]);

        foreach ($posts as $post) {
            $comments = $entityManager->getRepository(Comment::class)->findBy(['post' => $post]);

            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }
        }

        $entityManager->remove($userRow);
        $entityManager->flush();

        return $this->redirectToRoute('admin', [
            'user' => $userRow,
        ]);
    }




    /**
     * @Route("/post/delete/{id}", name="admin_delete_post")
     */
    public function deletePostRow(EntityManagerInterface $entityManager, Posts $post): Response
    {
        $postRow = $post;

        $postRow->getUser()->setPostCount($postRow->getUser()->getPostCount() - 1)->setCommentCount($postRow->getUser()->getCommentCount() - 1);

        $entityManager->remove($postRow);
        $entityManager->flush();






return $this->redirectToRoute('admin');

}

    /**
     * @Route("user/edit/{id}", name="admin_edit_user")
     */
    public function editUserRow(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, User $user): Response
    {

        //function edit user

        // retrieve user
        $userRow = $user;






        // create form on the edit page
        $form = $this->createForm(EditUserFormType::class, $userRow);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //hash edited password again
            $user = $form->getData();
            $plainPassword = $user->getPassword();

            if ($plainPassword) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $plainPassword
                    )
                );
            }

            //send the edited data to the database
            $entityManager->persist($userRow);
            $entityManager->flush();

            //redirect to the admin dashboard
            return $this->redirectToRoute('admin');
        }

        // get sent to the edit page of the user with the retrieved id
        return $this->render('admin/edit-user.html.twig',[
            'form' => $form->createView(),
        ]);
    }




    // edit post function

    /**
     * @Route("/post/edit/{id}", name="admin_edit_post")
     */
    public function editPostRow(Request $request, EntityManagerInterface $entityManager, Posts $post): Response
    {

    // retrieve the post
        $postRow = $post;

    //create a form to edit the post
        $form = $this->createForm(AdminPostEditFormType::class, $postRow);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //send the edited data to the database
            $entityManager->persist($postRow);
            $entityManager->flush();

            //redirect to the admin dashboard
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/edit-post.html.twig',[
            'formPost' => $form->createView(),
        ]);
    }


}
