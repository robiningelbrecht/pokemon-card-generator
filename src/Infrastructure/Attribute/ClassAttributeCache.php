<?php

namespace App\Infrastructure\Attribute;

class ClassAttributeCache
{
    private string $cacheFileName;

    public function __construct(
        private readonly string $attributeClassName,
        private readonly string $cacheDir,
    ) {
        $this->cacheFileName = rtrim($this->cacheDir, '/').'/'.(new \ReflectionClass($attributeClassName))->getShortName().'.php';
    }

    public function get(): string
    {
        if (!$this->exists()) {
            throw new \RuntimeException(sprintf('Cache not set for %s', (new \ReflectionClass($this->attributeClassName))->getShortName()));
        }

        return $this->cacheFileName;
    }

    /**
     * @param string[] $classes
     */
    public function compile(array $classes): string
    {
        if ($this->exists()) {
            return $this->cacheFileName;
        }

        $fileContent = [
            '<?php',
            '',
            'return [',
            ...array_map(fn (string $class) => '  \''.$class.'\',', $classes),
            '];',
        ];

        $this->createCacheDirectory(dirname($this->cacheFileName));

        $written = file_put_contents($this->cacheFileName, implode("\n", $fileContent));
        if (false === $written) {
            @unlink($this->cacheFileName);
            throw new \InvalidArgumentException(sprintf('Error while writing to %s', $this->cacheFileName));
        }

        return $this->cacheFileName;
    }

    public function exists(): bool
    {
        return file_exists($this->cacheFileName);
    }

    private function createCacheDirectory(string $directory): void
    {
        if (!is_dir($directory) && !@mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('Cache directory does not exist and cannot be created: %s.', $directory));
        }
        if (!is_writable($directory)) {
            throw new \InvalidArgumentException(sprintf('Cache directory is not writable: %s.', $directory));
        }
    }
}
