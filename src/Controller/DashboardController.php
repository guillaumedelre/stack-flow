<?php

namespace App\Controller;

use App\Service\DeveloperStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
