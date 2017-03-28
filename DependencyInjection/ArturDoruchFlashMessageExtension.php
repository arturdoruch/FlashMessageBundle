<?php

namespace ArturDoruch\FlashMessageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */
class ArturDoruchFlashMessageExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $templatingHelper = $container->getDefinition('arturdoruch_flash_message.templating_helper');
        $templatingHelper->addMethodCall('setMessageClassNames', array($config['classes']));
    }
}
