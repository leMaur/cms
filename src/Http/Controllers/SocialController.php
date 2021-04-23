<?php

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Lemaur\Cms\Models\Navigation;

class SocialController
{
    public function __invoke(string $social): RedirectResponse
    {
        // @TODO: extract to SocialRepository and cache it
        $url = Navigation::withType(Navigation::SOCIAL)
            ->where(DB::raw('LOWER(name)'), '=', $social)
            ->firstOrFail()
            ->url;

        return Redirect::away($url);
    }
}
