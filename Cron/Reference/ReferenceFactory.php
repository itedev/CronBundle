<?php

namespace ITE\CronBundle\Cron\Reference;

/**
 * Class ReferenceFactory
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ReferenceFactory
{

    public static function createListenerReference($resourcePath, $service, $method, $schedule = null, $priority = 0)
    {
        return new ListenerReference($resourcePath, $service, $method, $schedule, $priority);
    }

    public static function createCommandReference($resourcePath, $command, $parameters = '', $schedule = null, $priority = 0)
    {
        return new CommandReference($resourcePath, $command, $parameters, $schedule, $priority);
    }

}
