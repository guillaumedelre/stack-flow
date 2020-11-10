<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GitlabClient
{
    private HttpClientInterface $api;
    private string $gitlabSecret;

    public function __construct(string $gitlabUrl, string $gitlabSecret)
    {
        $this->api = HttpClient::createForBaseUri($gitlabUrl);
        $this->gitlabSecret = $gitlabSecret;
    }

    public function getUser(int $userId): array
    {
        $options = [
            'verify_peer' => false,
            'headers' => [
                'accept' => 'application/json',
            ],
            'query' => [
                'private_token' => $this->gitlabSecret,
            ]
        ];
        $response = $this->api->request('GET', "/api/v4/users/$userId", $options);
        return $response->toArray();
    }

    public function getOpened(): array
    {
        $options = [
            'verify_peer' => false,
            'headers' => [
                'accept' => 'application/json',
            ],
            'query' => [
                'private_token' => $this->gitlabSecret,
                'state' => "opened",
                'with_merge_status_recheck' => true,
                'order_by' => "updated_at",
            ]
        ];
        $response = $this->api->request('GET', "/api/v4/projects/128/merge_requests", $options);
        return $response->toArray();
    }

    public function getPipelines(string $sha): array
    {

        $options = [
            'verify_peer' => false,
            'headers' => [
                'accept' => 'application/json',
            ],
            'query' => [
                'private_token' => $this->gitlabSecret,
                'sha' => $sha,
                'order_by' => "updated_at",
            ]
        ];
        $response = $this->api->request('GET', "/api/v4/projects/128/pipelines", $options);
        return $response->toArray();
    }

    public function getAwardEmoji(int $iid): array
    {

        $options = [
            'verify_peer' => false,
            'headers' => [
                'accept' => 'application/json',
            ],
            'query' => [
                'private_token' => $this->gitlabSecret,
            ]
        ];
        $response = $this->api->request('GET', "/api/v4/projects/128/merge_requests/$iid/award_emoji", $options);
        return $response->toArray();
    }

    public function getNotes(int $iid): array
    {

        $options = [
            'verify_peer' => false,
            'headers' => [
                'accept' => 'application/json',
            ],
            'query' => [
                'private_token' => $this->gitlabSecret,
            ]
        ];
        $response = $this->api->request('GET', "/api/v4/projects/128/merge_requests/$iid/notes", $options);
        return $response->toArray();
    }
}
