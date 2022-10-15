<?php

declare(strict_types=1);

namespace App\Core;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Exception;

class AbstractBus
{
    protected function throwException(Exception $exception): void
    {
        while ($exception instanceof HandlerFailedException) {
            $previousException = $exception->getPrevious();

            if (null !== $previousException) {
                $exception = $previousException;
            }
        }

        throw $exception;
    }
}
