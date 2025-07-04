<?php

namespace Guestcms\PluginManagement\Commands;

use Guestcms\PluginManagement\PluginManifest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:plugin:clear-compiled', 'Remove the compiled plugins file')]
class ClearCompiledCommand extends Command
{
    public function handle(PluginManifest $manifest): int
    {
        if (File::isFile($pluginPath = $manifest->getManifestFilePath())) {
            File::delete($pluginPath);
        }

        $this->components->info('Compiled plugins files removed successfully.');

        return self::SUCCESS;
    }
}
