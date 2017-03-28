<?php

namespace ArturDoruch\FlashMessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('artur_doruch_flash_message');

        $rootNode
            ->children()
                ->arrayNode('classes')
                    ->defaultValue(array())
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
