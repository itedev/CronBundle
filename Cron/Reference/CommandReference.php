<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 01.04.2015
 * Time: 11:53
 */

namespace ITE\CronBundle\Cron\Reference;


/**
 * Class CommandReference
 *
 * @package ITE\CronBundle\Cron\Reference
 */
class CommandReference extends CachedReference
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $arguments;

    /**
     * @param null   $resourcePath
     * @param string $name
     * @param string $arguments
     * @param null   $schedule
     * @param int    $priority
     */
    public function __construct($resourcePath, $name, $arguments = '', $schedule = null, $priority = 0)
    {
        $this->name = $name;
        $this->arguments = $arguments;
        $this->schedule = $schedule;
        $this->priority = $priority;

        parent::__construct($resourcePath);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}