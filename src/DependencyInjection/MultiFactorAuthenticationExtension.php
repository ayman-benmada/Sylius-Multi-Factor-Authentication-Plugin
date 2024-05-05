<?php

declare(strict_types=1);

namespace Abenmada\MultiFactorAuthenticationPlugin\DependencyInjection;

use function array_merge;
use function array_pop;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class MultiFactorAuthenticationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = $container->getExtensionConfig('doctrine_migrations');
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => array_merge(array_pop($doctrineConfig)['migrations_paths'] ?? [], ['Abenmada\MultiFactorAuthenticationPlugin\Migrations' => '@MultiFactorAuthenticationPlugin/Migrations']),
        ]);
    }

    public function getAlias(): string
    {
        return 'abenmada_multi_factor_authentication_plugin';
    }
}
