<?php

namespace Guestcms\Newsletter\Http\Controllers;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Forms\FieldOptions\CheckboxFieldOption;
use Guestcms\Base\Forms\FieldOptions\EmailFieldOption;
use Guestcms\Base\Forms\Fields\CheckboxField;
use Guestcms\Base\Forms\Fields\EmailField;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Newsletter\Enums\NewsletterStatusEnum;
use Guestcms\Newsletter\Events\SubscribeNewsletterEvent;
use Guestcms\Newsletter\Events\UnsubscribeNewsletterEvent;
use Guestcms\Newsletter\Forms\Fronts\NewsletterForm;
use Guestcms\Newsletter\Http\Requests\NewsletterRequest;
use Guestcms\Newsletter\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PublicController extends BaseController
{
    public function postSubscribe(NewsletterRequest $request)
    {
        do_action('form_extra_fields_validate', $request, NewsletterForm::class);

        $newsletterForm = NewsletterForm::create();
        $newsletterForm->setRequest($request);

        $newsletterForm->onlyValidatedData()->saving(function (NewsletterForm $form): void {
            /**
             * @var NewsletterRequest $request
             */
            $request = $form->getRequest();

            /**
             * @var Newsletter $newsletter
             */
            $newsletter = $form->getModel()->newQuery()->firstOrNew([
                'email' => $request->input('email'),
            ], [
                ...$form->getRequestData(),
                'status' => NewsletterStatusEnum::SUBSCRIBED,
            ]);

            $newsletter->save();

            SubscribeNewsletterEvent::dispatch($newsletter);
        });

        return $this
            ->httpResponse()
            ->setMessage(__('Subscribe to newsletter successfully!'));
    }

    public function getUnsubscribe(int|string $id, Request $request)
    {
        abort_unless(URL::hasValidSignature($request), 404);

        /**
         * @var Newsletter $newsletter
         */
        $newsletter = Newsletter::query()
            ->where([
                'id' => $id,
                'status' => NewsletterStatusEnum::SUBSCRIBED,
            ])
            ->first();

        if ($newsletter) {
            $newsletter->update(['status' => NewsletterStatusEnum::UNSUBSCRIBED]);

            UnsubscribeNewsletterEvent::dispatch($newsletter);

            return $this
                ->httpResponse()
                ->setNextUrl(BaseHelper::getHomepageUrl())
                ->setMessage(__('Unsubscribe to newsletter successfully'));
        }

        return $this
            ->httpResponse()
            ->setError()
            ->setNextUrl(BaseHelper::getHomepageUrl())
            ->setMessage(__('Your email does not exist in the system or you have unsubscribed already!'));
    }

    public function ajaxLoadPopup()
    {
        $newsletterForm = NewsletterForm::create()
            ->remove(['wrapper_before', 'wrapper_after', 'email'])
            ->addBefore(
                'submit',
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email Address'))
                    ->maxLength(-1)
                    ->placeholder(__('Enter Your Email'))
                    ->required()
            )
            ->addAfter(
                'submit',
                'dont_show_again',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__("Don't show this popup again"))
                    ->value(false)
            );

        return $this
            ->httpResponse()
            ->setData([
                'html' => view('plugins/newsletter::partials.popup', compact('newsletterForm'))->render(),
            ]);
    }
}
