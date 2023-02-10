<?php

namespace App\Infrastructure\DependencyInjection;

interface CompilerPass
{
    public function process(ContainerBuilder $container): void;
}
