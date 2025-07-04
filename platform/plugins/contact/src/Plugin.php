<?php

namespace Guestcms\Contact;

use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('contact_custom_fields');
        Schema::dropIfExists('contact_custom_field_options');
        Schema::dropIfExists('contact_custom_fields_translations');
        Schema::dropIfExists('contact_custom_field_options_translations');
        Schema::dropIfExists('contact_replies');
        Schema::dropIfExists('contacts');

        Setting::delete([
            'blacklist_keywords',
            'blacklist_email_domains',
            'enable_math_captcha_for_contact_form',
        ]);
    }
}
