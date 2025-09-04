<?php

declare(strict_types=1);

namespace OpenAI\Symfony;

use OpenAI\Client;
use OpenAI\Contracts\ClientContract;
use OpenAI\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class OpenAIBundle extends AbstractBundle
{
    protected string $extensionAlias = 'openai';

    public function configure(DefinitionConfigurator $definition): void
    {
        $root = $definition->rootNode();
        assert($root instanceof ArrayNodeDefinition);
        $children = $root->children();
        $children->scalarNode('api_key')->defaultValue('%env(OPENAI_API_KEY)%');
        $children->scalarNode('organization')->defaultValue('%env(default::OPENAI_ORGANIZATION)%');
    }

    /**
     * @param  array{api_key: string, organization: string}  $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set('openai.http_client', Psr18Client::class)
            ->arg(0, service('http_client'))

            ->set(Factory::class)
            ->factory([\OpenAI::class, 'factory'])
            ->call('withHttpClient', [service('openai.http_client')])
            ->call('withHttpHeader', ['OpenAI-Beta', 'assistants=v2'])
            ->call('withApiKey', [$config['api_key']])
            ->call('withOrganization', [$config['organization']])

            ->set(Client::class)
            ->factory([service(Factory::class), 'make'])

            ->alias(ClientContract::class, Client::class)
            ->alias('openai', Client::class);
    }
}
