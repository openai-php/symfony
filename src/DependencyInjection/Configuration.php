<?php

declare(strict_types=1);

namespace OpenAI\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Jérôme Tamarelle <jerome@tamarelle.net>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('openai');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('api_key')->defaultValue('%env(OPENAI_API_KEY)%')->end()
                ->scalarNode('organization')->defaultValue('%env(default::OPENAI_ORGANIZATION)%')->end()
            ->end();

        return $treeBuilder;
    }
}
