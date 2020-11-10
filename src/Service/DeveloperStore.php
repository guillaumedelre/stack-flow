<?php

namespace App\Service;

use App\Model\Developer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class DeveloperStore implements SerializerAwareInterface
{
    use SerializerAwareTrait;

    private array $developers = [];

    public function __construct(array $teamConfiguration, GitlabClient $gitlabClient)
    {
        foreach ($teamConfiguration as $code => $config) {
            $this->developers[] = new Developer($code, $config['color'], $gitlabClient->getUser($config['gitlabId']));
        }
    }

    public function getDevelopers(bool $serialized = false)
    {
        return !$serialized
            ? $this->developers
            : $this->serializer->serialize($this->developers, 'json');
    }
}
