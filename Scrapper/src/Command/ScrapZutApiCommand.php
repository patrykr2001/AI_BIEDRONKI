<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ZutDataUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:scrap_zut_api', description: 'Run scrapper for ZUT API')]
class ScrapZutApiCommand extends Command
{
    public function __construct(private readonly ZutDataUpdater $zutDataUpdater)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->zutDataUpdater->updateOutput($output);
        $this->zutDataUpdater->updateZutData();

        return Command::SUCCESS;
    }

}
