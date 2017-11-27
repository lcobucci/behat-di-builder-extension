<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Tests;

final class TestContainerRemover
{
    public static function remove(): void
    {
        $tempDir = dirname(__DIR__) . '/tmp';

        if (! file_exists($tempDir)) {
            return;
        }

        foreach (glob($tempDir . '/*') as $file) {
            unlink($file);
        }

        rmdir($tempDir);
    }
}
