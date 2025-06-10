<?php

namespace Guestcms\SocialLogin\Http\Controllers;

use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\SocialLogin\Http\Requests\FacebookDataDeletionRequestCallbackRequest;
use Guestcms\SocialLogin\Supports\FacebookDataDeletionSignedRequestParser;
use Illuminate\Support\Str;

class FacebookDataDeletionRequestCallbackController extends BaseController
{
    public function store(
        FacebookDataDeletionRequestCallbackRequest $request,
        FacebookDataDeletionSignedRequestParser $signedRequestParser
    ) {
        $data = $signedRequestParser->parse($request->input('signed_request'));

        if (! $data) {
            return response()->json([
                'error' => 'Invalid signed request',
            ]);
        }

        return response()->json([
            'url' => route('facebook-deletion-status', ['id' => $confirmationCode = Str::uuid()]),
            'confirmation_code' => $confirmationCode,
        ]);
    }

    public function show(string $id)
    {
        abort_unless(Str::isUuid($id), 404);

        return response()->json([
            'status' => 'pending',
            'message' => 'Your data deletion request is pending. We will notify you once it is completed.',
        ]);
    }
}
