<?php

namespace App\Console;

use App\Infrastructure\Environment\Settings;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cache:clear', description: 'Clear all caches')]
class CacheClearConsoleCommand extends Command
{
    public function __construct(
        private readonly Settings $settings
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheDirs = [$this->settings->get('doctrine.cache_dir'), $this->settings->get('slim.cache_dir')];
        foreach ($cacheDirs as $cacheDir) {
            if (!file_exists($cacheDir)) {
                continue;
            }

            $this->removeDirectory($cacheDir);
        }

        return Command::SUCCESS;
    }

    private function removeDirectory(string $path): void
    {
        $files = glob($path.'/*');
        if (false === $files) {
            return;
        }

        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
    }
}
