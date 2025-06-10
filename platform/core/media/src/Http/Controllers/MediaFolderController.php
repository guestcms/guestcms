<?php

namespace Guestcms\Media\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Media\Facades\RvMedia;
use Guestcms\Media\Http\Requests\MediaFolderRequest;
use Guestcms\Media\Models\MediaFolder;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * @since 19/08/2015 07:55 AM
 */
class MediaFolderController extends BaseController
{
    public function store(MediaFolderRequest $request)
    {
        try {
            $name = $request->input('name');
            $parentId = $request->input('parent_id');

            MediaFolder::query()->create([
                'name' => MediaFolder::createName($name, $parentId),
                'slug' => MediaFolder::createSlug($name, $parentId),
                'parent_id' => $parentId,
                'user_id' => Auth::guard()->id(),
            ]);

            return RvMedia::responseSuccess([], trans('core/media::media.folder_created'));
        } catch (Exception $exception) {
            return RvMedia::responseError($exception->getMessage());
        }
    }
}
