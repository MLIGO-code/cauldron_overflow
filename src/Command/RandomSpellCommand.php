<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'Spellcaster',
    description: 'Cast a random spell',
)]
class RandomSpellCommand extends Command
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('your-name', InputArgument::OPTIONAL, 'Your Name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Do i have to yell the spell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $yourName = $input->getArgument('your-name');

        if ($yourName) {
            $io->note(sprintf('Hi  %s', $yourName));
        }

        $spells=[
            'alohomora',
            'crucio',
            'imperius',
            'expecto patronum',
            'wingardium leviosa',
            'castus spellus',
            'make me sandwich',
            'dont call'
        ];

        $spell=$spells[array_rand($spells)];

        if ($input->getOption('yell')) {
            $spell=strtoupper($spell);
        }
        $this->logger->info("Casting spell ". $spell);
        $io->success('Casting your spell right away : '.$spell );

        return Command::SUCCESS;
    }
}
