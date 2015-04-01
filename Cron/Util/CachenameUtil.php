<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 01.04.2015
 * Time: 13:43
 */

namespace ITE\CronBundle\Cron\Util;

/**
 * Class CacheNameUtil
 *
 * @package ITE\CronBundle\Cron\Util
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
     * @param $priority
     * @return string
     */
    public static function getCacheNameForCommand($pattern, $command, $priority)
    {
        return 'cron_command_'.md5(serialize([$pattern, $command, $priority])).'.meta';
    }

}