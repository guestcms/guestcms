<?php

namespace Guestcms\Table\Columns\Concerns;

enum CopyablePosition: string
{
    case Start = 'start';

    case End = 'end';
}
