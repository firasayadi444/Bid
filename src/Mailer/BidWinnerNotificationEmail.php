<?php

namespace App\Mailer;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class BidWinnerNotificationEmail extends TemplatedEmail
{
    public function __construct(string $winnerEmail, string $productName)
    {
        parent::__construct();

        $this->from(new Address('your_email@example.com', 'Your Name'))
            ->to(new Address($winnerEmail))
            ->subject('Congratulations! You Won the Bid')
            ->htmlTemplate('bid_winner_notification.html.twig')
            ->context([
                'productName' => $productName,
            ]);
    }

}