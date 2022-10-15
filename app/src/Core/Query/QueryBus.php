<?php

declare(strict_types=1);

namespace App\Core\Query;

use App\Core\AbstractBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

class QueryBus extends AbstractBus
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * @return mixed
     */
    public function handle(Query $query)
    {
        try {
            return $this->messageBus->dispatch($query)->last(HandledStamp::class)->getResult();
        } catch (Throwable $exception) {
            $this->throwException($exception);
        }
    }
}
