<?php

namespace Guestcms\Contact\Http\Requests\Settings;

use Guestcms\Base\Rules\EmailRule;
use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;

class ContactSettingRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $receiverEmails = $this->parseTagInputToArray('receiver_emails');
        $blacklistKeywords = $this->parseTagInputToArray('blacklist_keywords');

        if ($receiverEmails) {
            $this->merge([
                'receiver_emails_for_validation' => $receiverEmails,
            ]);
        }

        if ($blacklistKeywords) {
            $this->merge([
                'blacklist_keywords_for_validation' => $blacklistKeywords,
            ]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'blacklist_keywords' => ['nullable', 'string'],
            'receiver_emails' => ['nullable', 'string'],
            'contact_form_show_terms_checkbox' => new OnOffRule(),
        ];

        $newRules = [];

        if ($this->has('receiver_emails_for_validation')) {
            $newRules = [
                'receiver_emails_for_validation' => ['nullable', 'array'],
                'receiver_emails_for_validation.*' => [EmailRule::make()],
            ];
        }

        if ($this->has('blacklist_keywords_for_validation')) {
            $newRules = [
                ...$newRules,
                'blacklist_keywords_for_validation' => ['nullable', 'array'],
                'blacklist_keywords_for_validation.*' => ['required', 'string'],
            ];
        }

        if (! $newRules) {
            return $rules;
        }

        return [...$rules, ...$newRules];
    }

    protected function parseTagInputToArray(string $name): array
    {
        $data = $this->input($name);
        $data = is_string($data) ? trim($data) : '';

        if (! $data) {
            return [];
        }

        $data = collect(json_decode($data, true))
            ->pluck('value')
            ->all();

        if (! $data) {
            return [];
        }

        return $data;
    }

    public function attributes(): array
    {
        return [
            'receiver_emails_for_validation.*' => trans('plugins/contact::contact.settings.receiver_emails'),
            'blacklist_keywords_for_validation.*' => trans('plugins/contact::contact.settings.blacklist_keywords'),
            'contact_form_show_terms_checkbox' => trans('plugins/contact::contact.settings.show_terms_checkbox'),
        ];
    }
}
