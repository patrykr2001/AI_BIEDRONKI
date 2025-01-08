<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\ZutDataKinds;
use App\Service\ZutUrlBuilder;
use App\Service\ZutDataUpdater;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:scrap_zut_api', description: 'Run scrapper for ZUT API')]
class ScrapZutApiCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('url', InputArgument::OPTIONAL, 'The API URL to fetch data from',
            'https://plan.zut.edu.pl/');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('url');

        $client = HttpClient::create();
        $urlBuilder = new ZutUrlBuilder($url);
        $zutDataUpdater = new ZutDataUpdater($client, $urlBuilder, $output);
        $zutDataUpdater->updateZutData();

        return Command::SUCCESS;
    }

}
