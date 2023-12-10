<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    #[Route('/user/{iduser}', name: 'app_usr_profile', methods: ['GET'])]
    public function profile(ArticleRepository $articleRepository): Response
    {
        // Get the authenticated user
        $user = $this->getUser();

        // You can customize this part to load additional data related to the user (e.g., articles)
        // For example, assuming you have an Article entity and a relation between User and Article:
        // $articles = $user->getArticles();

        return $this->render('baseprofile.html.twig', [
            'user' => $user,
            'articles' => $articleRepository->findBy(['user' => $user])
        ]);
    }



}
