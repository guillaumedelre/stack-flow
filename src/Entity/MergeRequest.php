<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MergeRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *      itemOperations={"get"},
 *      collectionOperations={"get"},
 *      normalizationContext={"merge_request:get"}
 * )
 * @ORM\Entity(repositoryClass=MergeRequestRepository::class)
 */
class MergeRequest
{
    /**
     * @ApiProperty(identifier=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ApiProperty(identifier=true)
     * @ORM\Column(type="integer")
     */
    private int $gitlabId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $gitlabInternalId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $redmineId = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $sourceBranch;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $targetBranch;

    /**
     * @ORM\Column(type="json")
     */
    private array $upvotes = [];

    /**
     * @ORM\Column(type="json")
     */
    private array $downvotes = [];

    /**
     * @ORM\Column(type="json")
     */
    private array $author = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $hasConflicts = false;

    /**
     * @ORM\Column(type="integer")
     */
    private int $unresolvedBlockingDiscussions = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $blockingDiscussionsResolved = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $doNotMergeBitch = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $complexity;

    /**
     * @ORM\Column(type="json")
     */
    private array $pipeline = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): MergeRequest
    {
        $this->id = $id;
        return $this;
    }

    public function getGitlabId(): int
    {
        return $this->gitlabId;
    }

    public function setGitlabId(int $gitlabId): MergeRequest
    {
        $this->gitlabId = $gitlabId;
        return $this;
    }

    public function getGitlabInternalId(): int
    {
        return $this->gitlabInternalId;
    }

    public function setGitlabInternalId(int $gitlabInternalId): MergeRequest
    {
        $this->gitlabInternalId = $gitlabInternalId;
        return $this;
    }

    public function getRedmineId(): ?int
    {
        return $this->redmineId;
    }

    public function setRedmineId(?int $redmineId = null): MergeRequest
    {
        $this->redmineId = $redmineId;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): MergeRequest
    {
        $this->title = $title;
        return $this;
    }

    public function getSourceBranch(): string
    {
        return $this->sourceBranch;
    }

    public function setSourceBranch(string $sourceBranch): MergeRequest
    {
        $this->sourceBranch = $sourceBranch;
        return $this;
    }

    public function getTargetBranch(): string
    {
        return $this->targetBranch;
    }

    public function setTargetBranch(string $targetBranch): MergeRequest
    {
        $this->targetBranch = $targetBranch;
        return $this;
    }

    public function getUpvotes(): array
    {
        return $this->upvotes;
    }

    public function setUpvotes(array $upvotes): MergeRequest
    {
        $this->upvotes = $upvotes;
        return $this;
    }

    public function getDownvotes(): array
    {
        return $this->downvotes;
    }

    public function setDownvotes(array $downvotes): MergeRequest
    {
        $this->downvotes = $downvotes;
        return $this;
    }

    public function getAuthor(): array
    {
        return $this->author;
    }

    public function setAuthor(array $author): MergeRequest
    {
        $this->author = $author;
        return $this;
    }

    public function isHasConflicts(): bool
    {
        return $this->hasConflicts;
    }

    public function setHasConflicts(bool $hasConflicts): MergeRequest
    {
        $this->hasConflicts = $hasConflicts;
        return $this;
    }

    public function getUnresolvedBlockingDiscussions(): int
    {
        return $this->unresolvedBlockingDiscussions;
    }

    public function setUnresolvedBlockingDiscussions(int $unresolvedBlockingDiscussions): MergeRequest
    {
        $this->unresolvedBlockingDiscussions = $unresolvedBlockingDiscussions;
        return $this;
    }

    public function isBlockingDiscussionsResolved(): bool
    {
        return $this->blockingDiscussionsResolved;
    }

    public function setBlockingDiscussionsResolved(bool $blockingDiscussionsResolved): MergeRequest
    {
        $this->blockingDiscussionsResolved = $blockingDiscussionsResolved;
        return $this;
    }

    public function getComplexity(): ?int
    {
        return $this->complexity;
    }

    public function setComplexity(?int $complexity): MergeRequest
    {
        $this->complexity = $complexity;
        return $this;
    }

    public function getPipeline(): array
    {
        return $this->pipeline;
    }

    public function setPipeline(array $pipeline): MergeRequest
    {
        $this->pipeline = $pipeline;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): MergeRequest
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): MergeRequest
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getProgress(): int
    {
        return (count($this->upvotes) + ((int) !$this->hasConflicts) + ((bool) 'success' === ($this->pipeline['status'] ?? null))) * 20;
    }

    public function isDoNotMergeBitch(): bool
    {
        return $this->doNotMergeBitch;
    }

    public function setDoNotMergeBitch(bool $doNotMergeBitch): MergeRequest
    {
        $this->doNotMergeBitch = $doNotMergeBitch;
        return $this;
    }
}
