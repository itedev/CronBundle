<?php

namespace ITE\CronBundle\Cron\Reference;

use Cron\Schedule\CrontabSchedule;

/**
 * Class CachedReference
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CachedReference extends AbstractReference
{
    /**
     * @var string
     */
    private $resourcePath;

    /**
     * @var bool
     */
    private $loaded = false;

    public function __construct($resourcePath = null)
    {
        $this->resourcePath = $resourcePath;
    }

    public function getSchedule()
    {
        $this->load();

        return parent::getSchedule();
    }

    public function getPriority()
    {
        $this->load();

        return parent::getPriority();
    }

    /**
     * Load data from cache if needed
     */
    protected function load()
    {
        if ($this->loaded) {
            return;
        }

        $cacheData = unserialize(file_get_contents($this->resourcePath));

        $this->schedule = $cacheData['schedule'];
        $this->priority = $cacheData['priority'];

        $this->loaded = true;
    }

    /**
     * Save data to cache
     */
    public function save()
    {
        if (!is_object($this->schedule)) {
            $this->schedule = new CrontabSchedule($this->schedule);
        }

        $cacheData = serialize(['schedule' => $this->schedule, 'priority' => $this->priority]);

        file_put_contents($this->resourcePath, $cacheData);
    }

}
