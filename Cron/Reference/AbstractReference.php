<?php

namespace ITE\CronBundle\Cron\Reference;

use Cron\Schedule\CrontabSchedule;

/**
 * Class AbstractReference
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractReference implements ReferenceInterface
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * @var CrontabSchedule
     */
    protected $schedule;

    /**
     * @param CrontabSchedule $schedule
     * @param int             $priority
     */
    function __construct($schedule = null, $priority = 0)
    {
        $this->schedule = $schedule;
        $this->priority = $priority;
    }

    /**
     * @return CrontabSchedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
