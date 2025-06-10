<?php

namespace Guestcms\Contact\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\EmailHandler;
use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Contact\Enums\ContactStatusEnum;
use Guestcms\Contact\Forms\ContactForm;
use Guestcms\Contact\Http\Requests\ContactReplyRequest;
use Guestcms\Contact\Http\Requests\EditContactRequest;
use Guestcms\Contact\Models\Contact;
use Guestcms\Contact\Models\ContactReply;
use Guestcms\Contact\Tables\ContactTable;
use Illuminate\Validation\ValidationException;

class ContactController extends BaseController
{
    public function index(ContactTable $dataTable)
    {
        $this->pageTitle(trans('plugins/contact::contact.menu'));

        return $dataTable->renderTable();
    }

    public function edit(Contact $contact)
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/contact::contact.menu'), route('contacts.index'));

        $this->pageTitle(trans('plugins/contact::contact.edit'));

        return ContactForm::createFromModel($contact)->renderForm();
    }

    public function update(Contact $contact, EditContactRequest $request)
    {
        ContactForm::createFromModel($contact)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('contacts.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Contact $contact)
    {
        return DeleteResourceAction::make($contact);
    }

    public function postReply(Contact $contact, ContactReplyRequest $request)
    {
        $message = BaseHelper::clean($request->input('message'));

        if (! $message) {
            throw ValidationException::withMessages(['message' => trans('validation.required', ['attribute' => 'message'])]);
        }

        EmailHandler::send($message, sprintf('Re: %s', $contact->subject), $contact->email);

        ContactReply::query()->create([
            'message' => $message,
            'contact_id' => $contact->getKey(),
        ]);

        $contact->status = ContactStatusEnum::READ();
        $contact->save();

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/contact::contact.message_sent_success'));
    }
}
