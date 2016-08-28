<?php

namespace Yaroslav\JsonMapperBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class YaroslavJsonMapperExtension extends Extension
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
        $this->loadJsonMappers($config, $container);
    }
    
    
    /**
     * Под каждую описанную конфигурацию сгенерим сервис.
     * @param array $configs
     * @param ContainerBuilder $container
     */
    protected function loadJsonMappers(array $configs, ContainerBuilder $container) {
        foreach ($configs['mappers'] as $key => $mapper) {
            $mapperDefinition = new Definition('%yaroslav_json_mapper.json_mapper.class%');
            $mapperDefinition->addArgument($mapper['url']);
            $mapperDefinition->addArgument($mapper['mapClass']);
            $mapperDefinition->addArgument($mapper['mapping']);
            $mapperDefinition->addArgument(new Definition($mapper['adapter']));
            $nameDefintion = sprintf($this->getAlias() . '_%s', $key);
            $container->setDefinition($nameDefintion, $mapperDefinition);
        }        
    }
    
    public function getAlias() {
        return 'yaroslav_json_mapper';
    }
    
}
