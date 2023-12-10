<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
#[Route('/profile')]

class UserController extends AbstractController
{

    #[Route('/{iduser}', name: 'app_usr_profile', methods: ['GET'])]
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
    #[Route('/edit', name: 'user_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserInterface $user): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profile');
        }
        return $this->renderForm('profile/setting.html.twig', [
            'form' => $form,
        ]);
    }



}
