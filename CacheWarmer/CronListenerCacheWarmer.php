<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 17:00
 */

namespace ITE\CronBundle\CacheWarmer;

use Cron\Schedule\CrontabSchedule;
use Doctrine\Common\Annotations\AnnotationReader;
use ITE\CronBundle\Annotation\CronCommand;
use ITE\CronBundle\Cron\CronManager;
use ITE\CronBundle\Cron\Reference\CommandReference;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class CronListenerCacheWarmer
 *
 * @package ITE\CronBundle\CacheWarmer
 */
class CronListenerCacheWarmer implements CacheWarmerInterface
{

    const CACHE_DIR_SUFFIX = '/ite_cron/';

    /**
     * @var CronManager
     */
    protected $cronManager;

    public function __construct(CronManager $cronManager)
    {
        $this->cronManager = $cronManager;
    }


    /**
     * Checks whether this warmer is optional or not.
     *
     * Optional warmers can be ignored on certain conditions.
     *
     * A warmer should return true if the cache can be
     * generated incrementally and on-demand.
     *
     * @return bool true if the warmer is optional, false otherwise
     */
    public function isOptional()
    {
        return false;
    }

    public function warmUp($cacheDir)
    {

        $fileSystem = new Filesystem();
        $fileSystem->mkdir($cacheDir.self::CACHE_DIR_SUFFIX);

        $listeners = $this->cronManager->getListeners();

        foreach ($listeners as $listener) {
            $listener->save();
        }

        $commands = $this->cronManager->getCommands();

        foreach ($commands as $command) {
            $command->save();
        }

    }

}