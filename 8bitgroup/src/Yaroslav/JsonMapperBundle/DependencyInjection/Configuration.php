<?php

namespace Yaroslav\JsonMapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('yaroslav_json_mapper');

        $rootNode->children()
                        ->arrayNode('mappers')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('key')
                            ->prototype('array')
                            ->children()
                                ->scalarNode('url')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('mapClass')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('adapter')
                                    ->defaultValue('%yaroslav_json_mapper.default_adapter.class%')
                                ->end()
                                ->variableNode('mapping')
                                    // TODO: make recursive building of mapping config
                                ->end()
                            ->end()
                        ->end()
                    ->end();
        return $treeBuilder;
    }
}
