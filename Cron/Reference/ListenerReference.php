<?php

namespace ITE\CronBundle\Cron\Reference;

/**
 * Class ListenerReference
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ListenerReference extends CachedReference
{
    /**
     * @var object
     */
    private $service;

    /**
     * @var string
     */
    private $method;

    /**
     * @param null   $resourcePath
     * @param object $service
     * @param string $method
     * @param        $schedule
     * @param        $priority
     */
    public function __construct($resourcePath, $service, $method, $schedule = null, $priority = 0)
    {
        $this->service  = $service;
        $this->method   = $method;
        $this->schedule = $schedule;
        $this->priority = $priority;

        parent::__construct($resourcePath);
    }

    /**
     * @return object
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

}
