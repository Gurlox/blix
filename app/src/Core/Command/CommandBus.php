<?php

declare(strict_types=1);

namespace App\Core\Command;

use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function handle(Command $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
