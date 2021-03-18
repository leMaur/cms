<?php

namespace Lemaur\Cms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lemaur\Cms\Cms
 */
class CmsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-cms';
    }
}
