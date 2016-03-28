<?php

namespace ITE\CronBundle\Cron\Reference;

use Cron\Schedule\CrontabSchedule;

/**
 * Interface ReferenceInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
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
