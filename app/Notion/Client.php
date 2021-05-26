<?php

namespace App\Notion;

use Illuminate\Support\Facades\Http;

class Client
{
    private string $apiToken;

    private string $databaseId;

    private string $baseUrl = 'https://api.notion.com/v1';

    public function __construct(array $config)
    {
        $this->apiToken = $config['token'];
        $this->databaseId = $config['database-id'];
    }

    public function addEntryToDatabase($properties)
    {
        $parent = [
            'database_id' => $this->databaseId,
        ];

        $this->request()->post("{$this->baseUrl}/pages", [
            'parent' => $parent,
            'properties' => $properties,
        ])->throw();
    }

    public function queryDatabase(array $query):array
    {
        $url = "{$this->baseUrl}/databases/{$this->databaseId}/query";

        return $this->request()
            ->post($url, $query)
            ->throw()
            ->json('results');
    }

    protected function request()
    {
        return Http::withToken($this->apiToken)
            ->contentType('application/json')
            ->withHeaders([
                'Notion-Version' => '2021-05-13',
            ]);
    }
}
