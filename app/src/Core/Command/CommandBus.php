<?php

declare(strict_types=1);

namespace App\Core\Command;

use App\Core\AbstractBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class CommandBus extends AbstractBus
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function handle(Command $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (Throwable $exception) {
            $this->throwException($exception);
        }
    }
}
