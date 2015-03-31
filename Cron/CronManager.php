<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 16:58
 */

namespace ITE\CronBundle\Cron;


use Symfony\Component\Config\Loader\LoaderInterface;

class CronManager
{
    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @param LoaderInterface $loader
     * @param string          $cacheDir
     */
    public function __construct(LoaderInterface $loader, $cacheDir)
    {
        $this->loader   = $loader;
        $this->cacheDir = $cacheDir;
    }

    public function runCron()
    {
        $listeners = $this->loader->load($this->cacheDir . '/ite_cache/cron_listeners.meta');

    }
}