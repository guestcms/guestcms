<?php

namespace Guestcms\Base\Contracts;

interface GlobalSearchableResult
{
    public function getTitle(): string;

    public function getDescription(): string;

    public function getUrl(): string;

    public function getParents(): array;

    public function shouldOpenNewTab(): bool;
}
