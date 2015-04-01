<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 01.04.2015
 * Time: 11:59
 */

namespace ITE\CronBundle\Cron\Reference;


use Cron\Schedule\CrontabSchedule;

interface ReferenceInterface
{
    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return CrontabSchedule
     */
    public function getSchedule();
}