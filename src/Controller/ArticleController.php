<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\FormTypeInterface;



#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('homepage.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/{iduser}/myarticle', name: 'app_usr_article_index', methods: ['GET'])]
    public function usrindex(ArticleRepository $articleRepository, UserInterface $user): Response
    {
        $articles = $articleRepository->findBy(['user' => $user]);

        return $this->render('article/myarticles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $user = $this->getUser(); // Assuming you are using Symfony's security system
        $article->setUser($user);
        $article->setDateDeb(new \DateTimeImmutable());

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDeb = $article->getDateDeb();
            $dateFin = $article->getDateFin();

            if ($dateFin <= $dateDeb) {
                $form->get('date_fin')->addError(new FormError('The "date_fin" should be greater than the "date_deb".'));
            } else
            { $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);}
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{article_id}', name: 'app_article_show', methods: ['GET'])]
    public function show(int $article_id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($article_id);

        if (!$article) {
            // Handle not found case, e.g., redirect or show an error
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
