<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 16:57
 */

namespace ITE\CronBundle\DependencyInjection\Compiler;


use ITE\CronBundle\CacheWarmer\CronListenerCacheWarmer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CronListenerPass
 *
 * @package ITE\CronBundle\DependencyInjection\Compiler
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
        if (!$container->hasDefinition('ite_cron.cache_warmer')) {
            return;
        }

        $definition = $container->getDefinition('ite_cron.cache_warmer');

        $taggedServices = $container->findTaggedServiceIds('ite_cron.listener');
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $priority = isset($tagAttributes['priority']) ? $tagAttributes['priority'] : 0;
                if (!isset($attributes['method'])) {
                    throw new ParameterNotFoundException('method', $id, 'tag attributes');
                }
                $definition->addMethodCall(
                    'addListener',
                    array(CronListenerCacheWarmer::LISTENER_TYPE_SERVICE, $id, $attributes['method'], $priority)
                );
            }
        }
    }

}