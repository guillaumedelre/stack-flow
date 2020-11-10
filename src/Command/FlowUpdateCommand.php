<?php

namespace App\Command;

use App\Entity\MergeRequest;
use App\Repository\MergeRequestRepository;
use App\Service\TeamManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class FlowUpdateCommand extends Command implements DenormalizerAwareInterface, SerializerAwareInterface
{
    use DenormalizerAwareTrait;
    use SerializerAwareTrait;

    protected static $defaultName = 'flow:update';

    private MergeRequestRepository $mergeRequestRepository;
    private TeamManager $teamManager;
    private PublisherInterface $publisher;
    private RouterInterface $router;
    private RequestContext $requestContext;

    public function __construct(TeamManager $teamManager, MergeRequestRepository $mergeRequestRepository, PublisherInterface $publisher, RouterInterface $router)
    {
        parent::__construct();
        $this->teamManager = $teamManager;
        $this->mergeRequestRepository = $mergeRequestRepository;
        $this->publisher = $publisher;
        $this->router = $router;
        $this->router->getContext()->setScheme('https')->setHttpsPort(9443);
    }

    protected function configure()
    {
        $this->setDescription('[FLOW] Get opened merge-requests from gitlab and update local database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orphans = new ArrayCollection($this->mergeRequestRepository->findAll());
        $collection = [];
        foreach ($this->teamManager->getOpened() as $mergeRequest) {
            $entity = $this->mergeRequestRepository->findOneBy(['gitlabId' => $mergeRequest['id']]);
            if (!empty($entity) && $orphans->contains($entity)) {
                $orphans->remove($orphans->indexOf($entity));
            }
            $context = empty($entity) ? [] : [AbstractNormalizer::OBJECT_TO_POPULATE => $entity,];
            /** @var MergeRequest $item */
            $item = $this->denormalizer->denormalize($mergeRequest, MergeRequest::class, 'jsonld', $context);
            $item->setRedmineId($mergeRequest['redmine_id'] ?? null);
            $item->setDoNotMergeBitch(false !== array_search('DNMB', $mergeRequest['labels']));
            $item->setHasConflicts($mergeRequest['has_conflicts']);
            $item->setUnresolvedBlockingDiscussions($mergeRequest['unresolved_blocking_discussions']);
            $item->setGitlabId($mergeRequest['id']);
            $item->setGitlabInternalId($mergeRequest['iid']);
            $item->setSourceBranch($mergeRequest['source_branch']);
            $item->setTargetBranch($mergeRequest['target_branch']);
            $item->setCreatedAt((new \DateTime())->setTimestamp(strtotime($mergeRequest['created_at'])));
            $item->setUpdatedAt((new \DateTime())->setTimestamp(strtotime($mergeRequest['updated_at'])));
            $collection[] = $item;
        }

        $removeIds = $this->mergeRequestRepository->removeCollection($orphans->toArray());
        $update = new Update(
            $this->router->generate('api_merge_requests_get_collection', [], RouterInterface::ABSOLUTE_URL),
            $this->serializer->serialize(['orphans' => $removeIds], 'json')
        );
        ($this->publisher)($update);

        foreach ($this->mergeRequestRepository->saveCollection($collection) as $entity) {
            $update = new Update(
                $this->router->generate('api_merge_requests_get_item', ['id' => $entity->getId()], RouterInterface::ABSOLUTE_URL),
                $this->serializer->serialize($entity, 'json')
            );
            ($this->publisher)($update);
        }

        return Command::SUCCESS;
    }
}
