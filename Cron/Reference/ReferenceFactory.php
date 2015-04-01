<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 01.04.2015
 * Time: 13:46
 */

namespace ITE\CronBundle\Cron\Reference;


class ReferenceFactory
{

    public static function createListenerReference($resourcePath, $service, $method, $schedule = null, $priority = 0)
    {
        return new ListenerReference($resourcePath, $service, $method, $schedule, $priority);
    }

    public static function createCommandReference($resourcePath, $command, $schedule = null, $priority = 0)
    {
        return new CommandReference($resourcePath, $command, $schedule, $priority);
    }

}