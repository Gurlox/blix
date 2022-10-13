<?php

declare(strict_types=1);

namespace App\Core\Query;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QueryBus
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
        return $this->messageBus->dispatch($query)->last(HandledStamp::class)->getResult();
    }
}
