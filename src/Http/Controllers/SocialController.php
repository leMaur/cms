<?php

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lemaur\Cms\Repositories\SocialRepository;

class SocialController
{
    public function __invoke(SocialRepository $social, string $slug): RedirectResponse
    {
        return Redirect::away($social->find($slug)->url);
    }
}
