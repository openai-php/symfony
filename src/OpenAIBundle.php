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
        $children
            ->scalarNode('api_key')
            ->defaultValue('%env(OPENAI_API_KEY)%')
            ->info('OpenAI API Key used to authenticate with the OpenAI API')
            ->isRequired();
        $children
            ->scalarNode('organization')
            ->info('OpenAI API Organization used to authenticate with the OpenAI API')
            ->defaultValue('%env(default::OPENAI_ORGANIZATION)%')
            ->info('');
        $children
            ->scalarNode('project')
            ->defaultNull()
            ->info('OpenAI API project');
        $children
            ->scalarNode('base_uri')
            ->defaultNull()
            ->info('OpenAI API base URL used to make requests. Defaults to: api.openai.com/v1');
    }

    /**
     * @param  array{api_key: string, organization: string, project: ?string, base_uri: ?string}  $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set('openai.http_client', Psr18Client::class)
            ->arg(0, service('http_client'));

        $factory = $container->services()
            ->set(Factory::class)
            ->factory([\OpenAI::class, 'factory'])
            ->call('withHttpClient', [service('openai.http_client')])
            ->call('withHttpHeader', ['OpenAI-Beta', 'assistants=v2'])
            ->call('withApiKey', [$config['api_key']])
            ->call('withOrganization', [$config['organization']]);
        if ($config['project']) {
            $factory->call('withProject', [$config['project']]);
        }
        if ($config['base_uri']) {
            $factory->call('withBaseUri', [$config['base_uri']]);
        }

        $container->services()
            ->set(Client::class)
            ->factory([service(Factory::class), 'make']);

        $container->services()
            ->alias(ClientContract::class, Client::class)
            ->alias('openai', Client::class);
    }
}
