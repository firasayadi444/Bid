<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Bid;
use App\Form\BidType;
use App\Repository\BidRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Types\TextType;

#[Route('/bid')]
class BidController extends AbstractController
{
    #[Route('/', name: 'app_bid_index', methods: ['GET'])]
    public function index(BidRepository $bidRepository): Response
    {
        return $this->render('bid/show.html.twig', [
            'bids' => $bidRepository->findAll(),
        ]);
    }

    #[Route('/new/{article_id}', name: 'app_bid_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $article_id): Response
    {
        $bid = new Bid();

        // Set the user from the session
        $user = $this->getUser();
        $bid->setUser($user);

        // Set the bid date
        $bid->setBidingdate(new \DateTimeImmutable());

        // Get the article using the passed article_id
        $article = $entityManager->getRepository(Article::class)->find($article_id);

        // Set the article for the bid
        $bid->setArticle($article);

        $form = $this->createForm(BidType::class, $bid, [
            'article' => $article, // Pass the article as an option
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('app_bid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bid/new.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(), // Pass the FormView object
        ]);
    }




    #[Route('/{id}', name: 'app_bid_show', methods: ['GET'])]
    public function show(Bid $bid): Response
    {
        return $this->render('bid/show.html.twig', [
            'bid' => $bid,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bid_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bid $bid, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_bid_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bid/edit.html.twig', [
            'bid' => $bid,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bid_delete', methods: ['POST'])]
    public function delete(Request $request, Bid $bid, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bid->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bid);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_bid_index', [], Response::HTTP_SEE_OTHER);
    }
}
