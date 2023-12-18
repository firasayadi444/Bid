<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ProfileType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
#[Route('/profile')]

class UserController extends AbstractController
{

    #[Route('/{iduser}', name: 'app_usr_profile', methods: ['GET'])]
    public function profile(): Response
    {
        // Get the authenticated user
        $user = $this->getUser();

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{iduser}/edit', name: 'user_profile_edit', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserInterface $user,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_usr_profile', ['iduser' => $user->getUserIdentifier()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/setting.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }




}
