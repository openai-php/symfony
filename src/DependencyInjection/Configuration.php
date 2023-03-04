<?php

declare(strict_types=1);

namespace OpenAI\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('openai');
        $rootNode = $treeBuilder->getRootNode();

        assert($rootNode instanceof ArrayNodeDefinition);

        $children = $rootNode->children();

        assert($children instanceof NodeBuilder);

        $children = $children->scalarNode('api_key')->defaultValue('%env(OPENAI_API_KEY)%')->end();

        assert($children instanceof NodeBuilder);

        $children = $children->scalarNode('organization')->defaultValue('%env(default::OPENAI_ORGANIZATION)%')->end();

        assert($children instanceof NodeBuilder);

        $children->end();

        return $treeBuilder;
    }
}
