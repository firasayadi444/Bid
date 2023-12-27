<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Mailer\BidWinnerNotificationEmail;
use App\Repository\BidRepository;
use Symfony\Component\Mailer\MailerInterface;

#[AsCommand(
    name: 'sendBidWinnerNotifications',
    description: 'Add a short description for your command',
)]
class SendBidWinnerNotificationsCommand extends Command
{
    private $bidRepository;
    private $mailer;

    public function __construct(BidRepository $bidRepository, MailerInterface $mailer)
    {
        parent::__construct();

        $this->bidRepository = $bidRepository;
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this->setName('app:send-bid-notifications')
            ->setDescription('Send bid winner notifications for ended bids');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //we  get the article  expired
        $articleexpired = $this->bidRepository->findEndedBids();

        // we get the winning user of the expired article from bid
        $winninguser = $this->bidRepository->getWinningUser($articleexpired);

        foreach ($winninguser as $user) {
            // Get the winner's email and product name
            $winnerEmail = $user->getUser()->getEmail();
            $articleName = $articleexpired->getArticle()->getTitre();

            // Send bid winner notification email
            $email = new BidWinnerNotificationEmail($winnerEmail, $articleName);
            $this->mailer->send($email);

            // Additional logic (update bid status, etc.)
            // ...

            // Output for console
            $output->writeln("Email sent to winner: $winnerEmail");
        }

        return Command::SUCCESS;
    }
}
