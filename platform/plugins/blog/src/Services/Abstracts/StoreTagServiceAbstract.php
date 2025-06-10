<?php

namespace Guestcms\Blog\Services\Abstracts;

use Guestcms\Blog\Models\Post;
use Illuminate\Http\Request;

abstract class StoreTagServiceAbstract
{
    abstract public function execute(Request $request, Post $post): void;
}
