<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\DTO\Secret\CreateSecretDTO;
use App\Repository\SecretRepository;
use App\State\Secret\CreateSecretStateProcessor;
use App\State\Secret\ListSecretProvider;
use Doctrine\ORM\Mapping as ORM;

#[
    ApiResource(
        operations: [
            new Post(
                uriTemplate: '/secret',
                input: CreateSecretDTO::class,
                processor: CreateSecretStateProcessor::class
            ),
            new Get(
                uriTemplate: '/secret/{hash}',
                requirements: ['hash' => '.+'],
                provider: ListSecretProvider::class
            ),
        ],
    )
]
#[ORM\Entity(repositoryClass: SecretRepository::class)]
class Secret
{
    #[ORM\Id]
    #[ORM\Column(length: 255, unique: true)]
    private string $hash;

    #[ORM\Column(length: 255)]
    private ?string $secretText = null;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column]
    private string $expiresAt;

    #[ORM\Column]
    private int $remainingViews;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getSecretText(): ?string
    {
        return $this->secretText;
    }

    public function setSecretText(?string $secretText): void
    {
        $this->secretText = $secretText;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(string $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getRemainingViews(): int
    {
        return $this->remainingViews;
    }

    public function setRemainingViews(int $remainingViews): void
    {
        $this->remainingViews = $remainingViews;
    }

    public function decreaseRemainingViews(): void
    {
        if ($this->remainingViews > 0) {
            $this->remainingViews--;
        }
    }
}
