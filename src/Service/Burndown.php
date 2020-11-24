<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class Burndown
{
    private string $currentSprint;
    private string $chart;

    public function __construct()
    {
        $client = HttpClient::createForBaseUri('http://devnvm.francemm.priv/');
        $html = $client->request('GET', 'burndown.php?p=MZ', ['verify_peer' => false])->getContent(false);
        $crawler = new Crawler($html);
        $this->currentSprint = $crawler->filter('html body #chart_title form select option:selected')->getNode(0)->nodeValue;
        $crawler->filter('html body #chart_title')->each(function (Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $crawler->filter('html body > br')->each(function (Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $this->chart = $crawler->outerHtml();
    }

    public function getCurrentSprint(): string
    {
        return $this->currentSprint;
    }

    public function getChart(): string
    {
        return $this->chart;
    }

}
