<?php

namespace ITE\CronBundle\Cron\Reference;

/**
 * Class CommandReference
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
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
