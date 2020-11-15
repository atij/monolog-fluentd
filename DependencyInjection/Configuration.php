<?php

namespace AdoreMe\MonologFluentdBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('monolog_fluentd');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
                 ->scalarNode('port')->defaultValue(24224)->end()
                 ->scalarNode('host')->defaultValue('localhost')->end()
                 ->scalarNode('level')->defaultValue(constant('Monolog\Logger::DEBUG'))->end()
                 ->booleanNode('bubble')->defaultValue(true)->end()
                 ->scalarNode('formatter')->end()
                 ->scalarNode('processor')->end()
                 ->end();

        return $treeBuilder;
    }
}
