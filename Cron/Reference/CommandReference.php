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
    private $parameters;

    /**
     * @param null   $resourcePath
     * @param string $name
     * @param string $parameters
     * @param null   $schedule
     * @param int    $priority
     */
    public function __construct($resourcePath, $name, $parameters = '', $schedule = null, $priority = 0)
    {
        $this->name = $name;
        $this->parameters = $parameters;
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
    public function getParameters()
    {
        return $this->parameters;
    }
}