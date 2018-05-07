<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

use function dirname;
use function file_exists;
use function is_dir;

final class TestContainerRemover
{
    public static function remove(): void
    {
        $tempDir = dirname(__DIR__) . '/tmp';

        if (! file_exists($tempDir)) {
            return;
        }

        self::removeDir($tempDir);
    }

    private static function removeDir(string $dir): void
    {
        foreach (glob($dir . '/*') as $path) {
            if (is_dir($path)) {
                self::removeDir($path);
                continue;
            }

            unlink($path);
        }

        rmdir($dir);
    }
}
