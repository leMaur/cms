<?php

namespace Lemaur\Cms\Commands;

use Illuminate\Console\Command;

class CmsCommand extends Command
{
    public $signature = 'laravel-cms';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
