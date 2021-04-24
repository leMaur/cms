<?php

namespace Lemaur\Cms\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Lemaur\Cms\Repositories\SocialRepository;

class SocialController
{
    public function __invoke(SocialRepository $socialRepo, string $social): RedirectResponse
    {
        return Redirect::away($socialRepo->find($social)->url);
    }
}
