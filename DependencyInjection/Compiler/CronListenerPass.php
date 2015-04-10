<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 16:57
 */

namespace ITE\CronBundle\DependencyInjection\Compiler;


use ITE\Common\Annotation\Finder;
use ITE\CronBundle\Annotation\CronCommand;
use ITE\CronBundle\CacheWarmer\CronListenerCacheWarmer;
use ITE\CronBundle\Cron\Util\CacheNameUtil;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CronListenerPass
 *
 */
class CronListenerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ite_cron.manager')) {
            return;
        }

        $definition = $container->getDefinition('ite_cron.manager');

        $taggedServices = $container->findTaggedServiceIds('ite_cron.listener');

        $cachePath = $container->getParameter('kernel.cache_dir').CronListenerCacheWarmer::CACHE_DIR_SUFFIX;

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $priority = isset($tagAttributes['priority']) ? $tagAttributes['priority'] : 0;

                if (!isset($attributes['method'])) {
                    throw new ParameterNotFoundException('method', $id, 'tag attributes');
                }

                if (!isset($attributes['pattern'])) {
                    throw new ParameterNotFoundException('pattern', $id, 'tag attributes');
                }

                $referenceDefinition = $container->getDefinition('ite_cron.listener_reference');

                $referenceDefinition->setArguments(
                    [
                        $cachePath.CacheNameUtil::getCacheNameForListener(
                            $attributes['pattern'],
                            $id,
                            $attributes['method'],
                            $priority
                        ),
                        new Reference($id),
                        $attributes['method'],
                        $attributes['pattern'],
                        $priority
                    ]
                );

                $definition->addMethodCall(
                    'addListener',
                    array($referenceDefinition)
                );
            }
        }

        $this->addCommandListeners($container, $cachePath, $definition);
    }

    /**
     * Load commands, annotated as CronCommands.
     *
     * @param ContainerBuilder $container
     * @param                  $cachePath
     * @param Definition       $definition
     */
    protected function addCommandListeners(ContainerBuilder $container, $cachePath, Definition $definition)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $finder = new Finder();

        foreach ($bundles as $name => $bundle) {

            $reflected = new \ReflectionClass($bundle);
            $dir = dirname($reflected->getFileName()).'/Command';
            if (!is_dir($dir)) {
                continue;
            }

            $annotationsMetadata = $finder->findAnnotationsInDir($dir);

            foreach ($annotationsMetadata as $metadata) {

                /** @var CronCommand $annotation */
                if ($annotation = $metadata->getClassAnnotation('ITE\CronBundle\Annotation\CronCommand')) {
                    $referenceDefinition = $container->getDefinition('ite_cron.command_reference');
                    $name                = $metadata->getReflected()->newInstance()->getName();

                    $referenceDefinition->setArguments(
                        [
                            $cachePath.CacheNameUtil::getCacheNameForCommand(
                                $annotation->pattern,
                                $name,
                                $annotation->parameters,
                                $annotation->priority
                            ),
                            $name,
                            $annotation->parameters,
                            $annotation->pattern,
                            $annotation->priority
                        ]
                    );

                    $definition->addMethodCall('addCommand', [$referenceDefinition]);
                }
            }
        }
    }

}