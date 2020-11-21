<?php

namespace App\Controller;

use App\Service\DeveloperStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(RouterInterface $router, DeveloperStore $developerStore): Response
    {
        return $this->render(
            'dashboard/index.html.twig',
            [
                'api_merge_request_base_uri' => $router->generate('api_merge_requests_get_collection', [], RouterInterface::ABSOLUTE_URL),
                'developers' => $developerStore->getDevelopers(true),
                'updateTopic' => "{$router->generate('api_merge_requests_get_collection', [], RouterInterface::ABSOLUTE_URL)}/{*}",
                'deleteTopic' => "{$router->generate('api_merge_requests_get_collection', [], RouterInterface::ABSOLUTE_URL)}",
            ]
        );
    }

    /**
     * @Route("/burndown", name="burndown")
     */
    public function burndown(): Response
    {
        $client = HttpClient::createForBaseUri('http://devnvm.francemm.priv/');
        $html = $client->request('GET', 'burndown.php?p=MZ', ['verify_peer' => false])->getContent(false);
        $crawler = new Crawler($html);
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
        return new Response($crawler->outerHtml());
    }
}
