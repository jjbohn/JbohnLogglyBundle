<?php

namespace Jbohn\Bundle\LogglyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jbohn_loggly');
        $rootNode
            ->children()
                ->scalarNode('input')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('secure')->defaultValue(true)->end()
                ->scalarNode('format')->defaultValue('string')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
