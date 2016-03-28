<?php

namespace ITE\CronBundle\Cron\Util;

/**
 * Class CacheNameUtil
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CacheNameUtil
{
    /**
     * Returns cache filename for listener
     *
     * @param $pattern
     * @param $class
     * @param $method
     * @param $priority
     * @return string
     */
    public static function getCacheNameForListener($pattern, $class, $method, $priority)
    {
        return 'cron_listener_'.md5(serialize([$pattern, $class, $method, $priority])).'.meta';
    }

    /**
     * Returns cache filename for command
     *
     * @param $pattern
     * @param $command
     * @param $arguments
     * @param $priority
     * @return string
     */
    public static function getCacheNameForCommand($pattern, $command, $arguments, $priority)
    {
        return 'cron_command_'.md5(serialize([$pattern, $command, $arguments, $priority])).'.meta';
    }
}

