<?php

declare(strict_types=1);

namespace Mtmd\Ga4\Cache;

use InvalidArgumentException;
use Mtmd\Ga4\CacheInterface;
use RuntimeException;

/**
 * JSON-file cache for sites with no database. Keep the directory OUTSIDE
 * the web root -- it holds an OAuth access token.
 */
final class FileCache implements CacheInterface
{
    public function __construct(private readonly string $directory)
    {
    }

    public function get(string $key): ?array
    {
        $file = $this->path($key);
        if (!is_file($file)) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($file), true);

        return is_array($decoded) ? $decoded : null;
    }

    public function set(string $key, array $value): void
    {
        $file = $this->path($key);
        $this->ensureDirectory();
        file_put_contents($file, json_encode($value, JSON_THROW_ON_ERROR), LOCK_EX);
        chmod($file, 0600);
    }

    private function path(string $key): string
    {
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $key)) {
            throw new InvalidArgumentException(
                'Cache key must contain only letters, digits, underscore and hyphen; got: ' . $key
            );
        }

        return $this->directory . '/' . $key . '.json';
    }

    private function ensureDirectory(): void
    {
        if (is_dir($this->directory)) {
            return;
        }
        if (!mkdir($this->directory, 0700, true) && !is_dir($this->directory)) {
            throw new RuntimeException('Could not create cache directory: ' . $this->directory);
        }
    }
}
