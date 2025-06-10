<?php

use Guestcms\Theme\Facades\Theme;

app()->booted(fn () => Theme::registerFacebookIntegration());
