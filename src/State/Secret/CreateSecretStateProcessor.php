<?php

namespace App\State\Secret;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\Secret\CreateSecretDTO;
use App\Entity\Secret;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class CreateSecretStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): Secret
    {
        assert($data instanceof CreateSecretDTO);

        $secret = new Secret();
        $this->setSecretData($data, $secret);
        $this->persistProcessor->process($secret, $operation, $uriVariables, $context);

        return $secret;
    }

    /**
     * @throws \Exception
     */
    private function setSecretData(CreateSecretDTO $data, Secret $secret): void
    {
        $secret->setHash(hash('sha256', uniqid('', true) . random_bytes(16)));
        $secret->setSecretText($data->getSecret());
        $secret->setCreatedAt(new \DateTime());
        $secret->setRemainingViews($data->getExpireAfterViews());
        $secret->setExpiresAt(
            $data->getExpireAfter() === 0 ? '0' : (new \DateTime())->modify(
                '+' . $data->getExpireAfter() . ' minutes')->format('Y-m-d H:i:s'
            )
        );
    }
}
