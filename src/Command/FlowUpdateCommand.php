<?php

namespace App\Command;

use App\Service\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FlowUpdateCommand extends Command
{
    protected static $defaultName = 'flow:update';

    private Updater $updater;

    public function __construct(Updater $updater)
    {
        parent::__construct();
        $this->updater = $updater;
    }

    protected function configure()
    {
        $this->setDescription('[FLOW] Get opened merge-requests from gitlab and update local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $loop = true;
        while ($loop) {
            sleep(5);
            try {
                $this->updater->process();
            } catch (\Throwable $exception) {
                $io->error($exception->getMessage());
                $loop = false;
            }
        }

        return Command::SUCCESS;
    }
}
