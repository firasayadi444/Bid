<?php

namespace App\Controller;
use App\Entity\Article;

use App\Repository\ArticleRepository;
use App\Repository\BidRepository;
use App\service\mailerservice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(): Response
    {
        return $this->render('sendmail.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
    #[Route('/{article_id}/mailer', name: 'app_mailersend')]
    public function send( BidRepository $bidRepository,  $article_id, mailerservice $mailer,
                        ArticleRepository $articleRepository): void
    {
//        $article = $articleRepository->find($article_id);
        $userwinning=$bidRepository->getWinningUser($article_id);
       $userwinneremail= $userwinning->getEmail();

        $mailer->sendEmail($userwinneremail);



    }
}
