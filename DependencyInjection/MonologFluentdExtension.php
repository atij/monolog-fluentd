<?php

namespace AdoreMe\MonologFluentdBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

class MonologFluentdExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $level = is_int($config['level'])
            ? $config['level']
            : constant('Monolog\Logger::' . strtoupper($config['level']));

        $handlerDefinition = new Definition();
        $handlerDefinition
            ->setClass($container->getParameter('monolog_fluentd.monolog_handler.class'))
            ->addArgument($config['port'])
            ->addArgument($config['host'])
            ->addArgument($level)
            ->addArgument($config['bubble'])
            ->addArgument($config['env']);

        if (isset($config['formatter']) && !empty($config['formatter'])) {
            $handlerDefinition->addMethodCall('setFormatter', [new Reference($config['formatter'])]);
        }

        if (isset($config['processor']) && !empty($config['processor'])) {
            $handlerDefinition->addMethodCall('pushProcessor', [new Reference($config['processor'])]);
        }

        $container->setDefinition('monolog_fluentd.monolog_handler', $handlerDefinition);
    }
}
