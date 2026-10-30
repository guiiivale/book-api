<?php

namespace App\Domain\Auth\Exceptions;

use DomainException;

final class TooManyAuthenticationAttemptsException extends DomainException
{
    public function __construct(public readonly int $retryAfterSeconds)
    {
        parent::__construct('Too many attempts. Please try again later.');
    }
}
