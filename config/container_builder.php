<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Behat\Config;

use Lcobucci\DependencyInjection\ContainerBuilder;

use function dirname;

return ContainerBuilder::default(__FILE__, __NAMESPACE__)
    ->setParameter('mode', 'test')
    ->setDumpDir(dirname(__DIR__) . '/tmp');
