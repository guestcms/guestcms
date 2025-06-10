<?php

namespace Guestcms\ACL\Http\Controllers\Auth;

use Guestcms\ACL\Forms\Auth\ResetPasswordForm;
use Guestcms\ACL\Traits\ResetsPasswords;
use Guestcms\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ResetPasswordController extends BaseController
{
    use ResetsPasswords;

    protected string $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');

        $this->redirectTo = route('dashboard.index');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $this->pageTitle(trans('core/acl::auth.reset.title'));

        return ResetPasswordForm::create()->renderForm();
    }
}
