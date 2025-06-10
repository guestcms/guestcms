<?php

namespace Guestcms\Blog\Http\Controllers\API;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Blog\Http\Resources\TagResource;
use Guestcms\Blog\Models\Tag;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    /**
     * List tags
     *
     * @group Blog
     */
    public function index(Request $request)
    {
        $data = Tag::query()
            ->wherePublished()
            ->with('slugable')
            ->paginate($request->integer('per_page', 10) ?: 10);

        return $this
            ->httpResponse()
            ->setData(TagResource::collection($data))
            ->toApiResponse();
    }
}
