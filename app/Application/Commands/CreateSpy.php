<?php

namespace App\Application\Commands;

use App\Domain\Models\Spy;

class CreateSpy
{
    public function __construct(public Spy $spy)
    {
    }
}
