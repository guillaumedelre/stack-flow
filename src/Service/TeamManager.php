<?php

namespace App\Service;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpClient\HttpClient;

class TeamManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private GitlabClient $gitlabClient;

    public function __construct(GitlabClient $gitlabClient)
    {
        $this->gitlabClient = $gitlabClient;
    }

    public function getOpened(): array
    {
        $mergeRequests = $this->gitlabClient->getOpened();
        foreach ($mergeRequests as &$mergeRequest) {
            $mergeRequest['complexity'] = null;
            $mergeRequest['unresolved_blocking_discussions'] = 0;
            foreach ($this->gitlabClient->getNotes($mergeRequest['iid']) as $note) {
                if (!$note['resolvable'] || $note['resolved']) {
                    continue;
                }
                $mergeRequest['unresolved_blocking_discussions']++;
            }
            $mergeRequest['upvotes'] = [];
            $mergeRequest['downvotes'] = [];
            foreach ($this->gitlabClient->getAwardEmoji($mergeRequest['iid']) as $awardEmoji) {
                switch ($awardEmoji['name']) {
                    case 'thumbsup':
                        $mergeRequest['upvotes'][] = $awardEmoji['user'];
                        break;
                    case 'thumbsdown':
                        $mergeRequest['downvotes'][] = $awardEmoji['user'];
                        break;
                }
            }
            $pipeline = current($this->gitlabClient->getPipelines($mergeRequest['sha']));
            $mergeRequest['pipeline'] = !$pipeline ? []: $pipeline;
            $redmineIdentifer = explode('/', $mergeRequest['source_branch'])[1];
            $redmineIdentiferIsValid = empty(preg_replace('/\d+/', '', $redmineIdentifer));
            if ($redmineIdentiferIsValid) {
                $response = HttpClient::create(
                    [
                        'base_uri' => 'https://projects.francemm.com',
                        'verify_peer' => false,
                        'headers' => [
                            'Accept' => 'application/json',
                            'X-Redmine-API-Key' => '3577bead3d8467eccc3da5e2525a084ab5c1ea41',
                        ]
                    ]
                )->request('GET', "/issues/$redmineIdentifer.json");
                if (200 === $response->getStatusCode()) {
                    $mergeRequest['redmine_id'] = (int) $redmineIdentifer;
                    foreach ($response->toArray()['issue']['custom_fields'] as $customField) {
                        if (28 !== $customField['id']) {
                            continue;
                        }
                        $mergeRequest['complexity'] = (int) $customField['value'];
                        break;
                    }
                }
            }
        }
        return $mergeRequests;
    }
}
