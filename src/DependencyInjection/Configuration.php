<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /** @psalm-suppress UnusedVariable */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('abenmada_multi_factor_authentication_plugin');
    }
}
