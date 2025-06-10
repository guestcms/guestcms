<?php

namespace Guestcms\Newsletter\Contracts;

interface Provider
{
    public function contacts(): array;

    public function subscribe(string $email, array $mergeFields = []): array;

    public function unsubscribe(string $email): array;
}
