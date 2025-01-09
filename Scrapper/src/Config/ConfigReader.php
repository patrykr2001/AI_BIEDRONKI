<?php

namespace App\Config;

class ConfigReader
{

    private array $config;

    public function __construct(string $configFilePath = 'config.php')
    {
        if (!file_exists($configFilePath)) {
            throw new \InvalidArgumentException("Config file not found: $configFilePath");
        }

        $this->config = require $configFilePath;

        if (!is_array($this->config)) {
            throw new \RuntimeException("Invalid configuration format in file: $configFilePath");
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function getApiBaseUrl(): string
    {
        return $this->get('api_base_url', 'https://plan.zut.edu.pl/');
    }

    public function getDateRange(): array
    {
        return $this->get('date_range', ['start' => '2025-01-01', 'end' => '2025-01-31']);
    }
}
