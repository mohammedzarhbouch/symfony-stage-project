<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('user/admin.html.twig');
    }
}