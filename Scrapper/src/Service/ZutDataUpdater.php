<?php

namespace App\Service;

use App\Enum\ZutEndpoints;
use App\Enum\ZutDataKinds;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZutDataUpdater{
    private HttpClientInterface $client;
    private ZutUrlBuilder $urlBuilder;
    private OutputInterface $output;

    public function __construct(HttpClientInterface $client, ZutUrlBuilder $urlBuilder, OutputInterface $output){
        $this->client = $client;
        $this->urlBuilder = $urlBuilder;
        $this->output = $output;
    }

    public function updateZutData(): void{
        $this->updateSpecificZutData(ZutDataKinds::Teachers);
        $this->updateSpecificZutData(ZutDataKinds::Groups);
        $this->updateSpecificZutData(ZutDataKinds::Subjects);
        $this->updateSpecificZutData(ZutDataKinds::Rooms);
    }

    private function updateSpecificZutData(ZutDataKinds $kind): void
    {
        $response = $this->client->request('GET', $this->urlBuilder->buildDataUrl($kind, ''), [
            'headers' => [
                'Accept-Charset' => 'UTF-8',
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            $this->output->writeln('<error>Failed to fetch '.$kind->name.' data from API.</error>');
            return;
        }

        $data = $response->getContent();
        $processedData = $this->processData($data);
        file_put_contents($kind->name.'.json', $processedData);

        $this->output->writeln('<info>Data successfully fetched and saved '.$kind->name.' data.</info>');
    }

    private function processData(string $jsonContent): string
    {
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON content.");
        }

        $formattedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $formattedJson;
    }
}
