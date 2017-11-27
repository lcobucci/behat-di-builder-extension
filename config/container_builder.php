<?php
declare(strict_types=1);

use Lcobucci\DependencyInjection\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->setParameter('mode', 'test')
        ->setDumpDir(dirname(__DIR__) . '/tmp');

return $builder;
