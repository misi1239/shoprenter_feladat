<?php

namespace App\DTO\Secret;

use Symfony\Component\Validator\Constraints as Assert;

class CreateSecretDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $secret;

    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[Assert\Type('integer')]
    private int $expireAfterViews;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\Type("integer")]
    private int $expireAfter;

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getExpireAfterViews(): int
    {
        return $this->expireAfterViews;
    }

    public function setExpireAfterViews(int $expireAfterViews): void
    {
        $this->expireAfterViews = $expireAfterViews;
    }

    public function getExpireAfter(): int
    {
        return $this->expireAfter;
    }

    public function setExpireAfter(int $expireAfter): void
    {
        $this->expireAfter = $expireAfter;
    }
}