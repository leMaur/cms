<?php

declare(strict_types=1);

namespace Lemaur\Cms\ViewModels\Contracts;

use Spatie\ViewModels\ViewModel;

interface Viewable
{
    public function toViewModel(): ViewModel;
}
