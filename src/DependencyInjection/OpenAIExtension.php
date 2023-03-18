<?php

declare(strict_types=1);

namespace OpenAI\Symfony\DependencyInjection;

use OpenAI\Factory;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @internal
 */
final class OpenAIExtension extends Extension
{
    /**
     * @param  array<int, array<int, mixed>>  $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        $configuration = $this->getConfiguration($configs, $container);

        assert($configuration instanceof ConfigurationInterface);

        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(Factory::class);
        $definition->addMethodCall('withApiKey', [$config['api_key']]);
        if ($config['organization']) {
            $definition->addMethodCall('withOrganization', [$config['organization']]);
        }
    }
}
