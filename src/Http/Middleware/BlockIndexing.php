<?php

declare(strict_types=1);

namespace Lemaur\Cms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlockIndexing
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ((bool) config('cms.seo.block_indexing')) {
            $response->header('X-Robots-Tag', 'noindex');
        }

        return $response;
    }
}
