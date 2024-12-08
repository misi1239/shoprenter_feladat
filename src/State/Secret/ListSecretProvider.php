<?php

namespace App\State\Secret;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Secret;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ListSecretProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * @throws \Exception
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?Secret
    {
        $secret = $this->entityManager->getRepository(Secret::class)->findOneBy(
            ['hash' => $uriVariables['hash'] ?? null]
        );

        if ($secret === null) {
            throw new NotFoundHttpException("The secret not found");
        }

        $this->getExpireAt($secret);
        $this->getRemainingViews($secret);

        return $secret;
    }

    private function getExpireAt(Secret $secret): void
    {
        if ($secret->getExpiresAt() === '0') {
            return;
        }

        $expireAt = \DateTime::createFromFormat('Y-m-d H:i:s', $secret->getExpiresAt());

        if ($expireAt < new \DateTime()) {
            throw new \Exception("The secret has expired.");
        }
    }

    private function getRemainingViews(Secret $secret): void
    {
        if ($secret->getRemainingViews() > 0) {
            $secret->decreaseRemainingViews();
            $this->entityManager->flush();
        } else {
            throw new \Exception("The secret has already been viewed the maximum number of times.");
        }
    }
}
