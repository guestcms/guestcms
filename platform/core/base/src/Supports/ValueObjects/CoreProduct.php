<?php

namespace Guestcms\Base\Supports\ValueObjects;

use Guestcms\Base\Supports\Core;
use Carbon\CarbonInterface;

class CoreProduct
{
    public function __construct(
        public string $updateId,
        public string $version,
        public CarbonInterface $releasedDate,
        public ?string $summary = null,
        public ?string $changelog = null,
        public bool $hasSQL = false
    ) {
    }

    public function hasUpdate(): bool
    {
        return version_compare($this->version, Core::make()->version(), '>');
    }
}
