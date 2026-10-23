<?php

namespace App\Domain\Books\Exceptions;

use DomainException;

final class BookAccessDeniedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Book not found.');
    }
}
