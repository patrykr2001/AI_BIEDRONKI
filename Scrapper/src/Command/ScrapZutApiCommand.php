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
    private ZutDataUpdater $zutDataUpdater;

    public function __construct(ZutDataUpdater $zutDataUpdater)
    {
        $this->zutDataUpdater = $zutDataUpdater;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->zutDataUpdater->updateOutput($output);
        $this->zutDataUpdater->updateZutData();
//        $this->zutDataUpdater->updateTeachersScheduleData();

        return Command::SUCCESS;
    }

}
