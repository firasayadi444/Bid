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
//    #[Route('/', name: 'app_bid_index', methods: ['GET'])]
//    public function bids(BidRepository $bidRepository): Response
//    {
//        $bids=>$bidRepository->findAll();
//
//        return $this->render('bid/show.html.twig', [
//            'bids'=>$bids ,
//        ]);
//
//    }

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
            'article' => $article,
            'bid' => $bid,
            'form' => $form->createView(), // Pass the FormView object
        ]);
    }




//    #[Route('/{id}', name: 'app_bid_show', methods: ['GET'])]
//    public function show(Bid $bid): Response
//    {
//        return $this->render('bid/show.html.twig', [
//            'bid' => $bid,
//        ]);
//    }
//    #[Route('/{id}', name: 'app_bid_show', methods: ['GET'])]
//    public function show(Article $article): Response
//    {
//        dump($article);
//        return $this->render('bid/show.html.twig', [
//            'article' => $article,
//        ]);
//    }
    #[Route('/{id}', name: 'app_bid_show', methods: ['GET'])]
    public function show(Article $article, BidRepository $bidRepository): Response
    {
        $user = $this->getUser();
        $bids = $bidRepository->findBy(['user' => $user, 'article' => $article]);
        return $this->render('bid/show.html.twig', [
            'article' => $article,
            'bids' => $bids,
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

//    #[Route('bid/{articleId}/article_bids/', name: 'article_bids', methods: ['GET'])]
//    public function articleBids(EntityManagerInterface $entityManager, $articleId ): Response
//    {        $user = $this->getUser(); // Assuming you are using Symfony's security system
//
//        // Fetch articles with their associated bids
//        $articlesWithBids = $this->$entityManager
//            ->getRepository(Article::class)
//            ->findBidsForUserAndArticle($user,$articleId ); // You need to define this custom method in your repository
//
//        return $this->render('bid/articlebids.html.twig', [
//            'articlesWithBids' => $articlesWithBids,
//        ]);
//    }





}
