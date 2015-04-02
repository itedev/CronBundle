<?php

namespace ITE\CronBundle\DependencyInjection;

use ITE\CronBundle\CacheWarmer\CronListenerCacheWarmer;
use ITE\CronBundle\Cron\Util\CacheNameUtil;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ITECronExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->loadListenersConfiguration($config, $container);
        $this->loadCommandsConfiguration($config, $container);
    }

    protected function loadListenersConfiguration(array $config, ContainerBuilder $container)
    {
        if (!empty($config['listeners'])) {

            $definition = $container->getDefinition('ite_cron.manager');
            $cachePath  = $container->getParameter('kernel.cache_dir').CronListenerCacheWarmer::CACHE_DIR_SUFFIX;

            foreach ($config['listeners'] as $listener) {
                $referenceDefinition = $container->getDefinition('ite_cron.listener_reference');

                $referenceDefinition->setArguments(
                    [
                        $cachePath.CacheNameUtil::getCacheNameForListener(
                            $listener['pattern'],
                            $listener['service'],
                            $listener['method'],
                            $listener['priority']
                        ),
                        new Reference($listener['service']),
                        $listener['method'],
                        $listener['pattern'],
                        $listener['priority']
                    ]
                );

                $definition->addMethodCall(
                    'addListener',
                    array($referenceDefinition)
                );
            }

        }
    }

    protected function loadCommandsConfiguration(array $config, ContainerBuilder $container)
    {
        if (!empty($config['commands'])) {

            $definition = $container->getDefinition('ite_cron.manager');
            $cachePath  = $container->getParameter('kernel.cache_dir').CronListenerCacheWarmer::CACHE_DIR_SUFFIX;

            foreach ($config['listeners'] as $listener) {
                $referenceDefinition = $container->getDefinition('ite_cron.command_reference');

                $referenceDefinition->setArguments(
                    [
                        $cachePath.CacheNameUtil::getCacheNameForCommand(
                            $listener['pattern'],
                            $listener['name'],
                            $listener['parameters'],
                            $listener['priority']
                        ),
                        $listener['name'],
                        $listener['parameters'],
                        $listener['pattern'],
                        $listener['priority']
                    ]
                );

                $definition->addMethodCall(
                    'addCommand',
                    array($referenceDefinition)
                );
            }

        }
    }
}
