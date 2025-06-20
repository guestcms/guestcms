<?php

namespace Guestcms\Base\Supports;

class Gravatar
{
    public static function image(?string $email, int $size = 200, string $rating = 'g', string $default = 'monsterid'): string
    {
        $id = md5(strtolower(trim($email)));

        return 'https://www.gravatar.com/avatar/' . $id . '/?d=' . $default . '&s=' . $size . '&r=' . $rating;
    }
}
