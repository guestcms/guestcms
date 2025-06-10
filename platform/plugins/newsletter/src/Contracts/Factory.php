<?php

namespace Guestcms\Newsletter\Contracts;

interface Factory
{
    public function driver(string $driver);
}
