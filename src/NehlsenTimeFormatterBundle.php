<?php declare(strict_types=1);

namespace nehlsen\TimeFormatterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NehlsenTimeFormatterBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
