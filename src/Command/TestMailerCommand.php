<?php
// src/Command/TestMailerCommand.php
// src/Command/TestMailerCommand.php

namespace App\Command;

use App\Service\mailerservice;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestMailerCommand extends Command
{
    private $mailerService;

    public function __construct(mailerservice $mailerService)
    {
        $this->mailerService = $mailerService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:test-mailer')
            ->setDescription('Send a test email using the mailer')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email address to send the test email to.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email') ?? 'saiedeya783@gmail.com';

        // Add logic to send a test email
        $this->mailerService->sendEmail($email);

        $output->writeln('Test email sent successfully.');
    }


}
