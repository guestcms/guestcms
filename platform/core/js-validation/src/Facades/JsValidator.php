<?php

namespace Guestcms\JsValidation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\JsValidation\Javascript\JavascriptValidator make(array $rules, array $messages = [], array $customAttributes = [], string|null $selector = null)
 * @method static \Guestcms\JsValidation\Javascript\JavascriptValidator formRequest($formRequest, $selector = null)
 * @method static \Guestcms\JsValidation\Javascript\JavascriptValidator validator(\Illuminate\Validation\Validator $validator, string|null $selector = null)
 *
 * @see \Guestcms\JsValidation\JsValidatorFactory
 */
class JsValidator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'js-validator';
    }
}
