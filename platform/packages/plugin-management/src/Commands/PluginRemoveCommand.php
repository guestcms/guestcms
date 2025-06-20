<?php

namespace Guestcms\PluginManagement\Commands;

use Guestcms\PluginManagement\Commands\Concern\HasPluginNameValidation;
use Guestcms\PluginManagement\Services\PluginService;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand('cms:plugin:remove', 'Remove a plugin in the /platform/plugins directory.')]
class PluginRemoveCommand extends Command implements PromptsForMissingInput
{
    use ConfirmableTrait;
    use HasPluginNameValidation;

    public function handle(PluginService $pluginService): int
    {
        if (! $this->confirmToProceed('Are you sure you want to permanently delete?', true)) {
            return self::FAILURE;
        }

        $name = $this->argument('name');

        $name = rtrim($name, '/');

        $this->validatePluginName($name);

        $plugin = Str::afterLast(strtolower($name), '/');

        $result = $pluginService->remove($plugin);

        if ($result['error']) {
            $this->components->error($result['message']);

            return self::FAILURE;
        }

        $this->components->info($result['message']);

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The plugin that you want to remove');
        $this->addOption('force', 'f', null, 'Force to remove plugin without confirmation');
    }
}
