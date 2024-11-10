<?php

namespace App\Application\Contracts;

use App\Application\Commands\CreateSpy;
use App\Domain\Models\Spy;

interface CreateSpyActionContract
{
    public function execute(CreateSpy $command): Spy;
}
