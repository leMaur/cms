<?php

declare(strict_types=1);

namespace Lemaur\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PublishCommand extends Command
{
    protected $signature = 'cms:install
                            {--F|force}';

    protected $description = '';

    public function handle(Filesystem $filesystem): int
    {
        $this->copyFiles($filesystem, [
            __DIR__.'/../Console/stubs/database/seeders/PageSeeder.php.stub' => base_path('database/seeders/PageSeeder.php'),
            __DIR__.'/../Console/stubs/database/seeders/NavigationSeeder.php.stub' => base_path('database/seeders/NavigationSeeder.php'),
        ]);

        return 0;
    }

    private function copyFiles(Filesystem $filesystem, array $files): void
    {
        collect($files)->each(function ($published, $original) use ($filesystem) {
            if (! $this->option('force') && $filesystem->exists($published)) {
                $this->error("The file [$published] already exists.");
            }

            $path = Str::beforeLast($published, '/');
            $filesystem->ensureDirectoryExists($path);
            $filesystem->copy($original, $published);
        });

        $this->info("Successfully published all files!");
    }
}
