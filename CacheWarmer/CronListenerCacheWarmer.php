<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 17:00
 */

namespace ITE\CronBundle\CacheWarmer;

use Doctrine\Common\Annotations\AnnotationReader;
use ITE\CronBundle\Annotation\CronCommand;
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
    const LISTENER_TYPE_SERVICE = 1;
    const LISTENER_TYPE_COMMAND = 2;
    /**
     * @var array
     */
    private $listeners = [];

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @param $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
    }


    /**
     * Add listener to warmUp
     *
     * @param      $type
     * @param      $cronPattern
     * @param      $service
     * @param null $method
     * @param int  $priority
     */
    public function addListener($type, $cronPattern, $service, $method = null, $priority = 0)
    {
        $this->listeners[$cronPattern] = [
            'type'     => $type,
            'service'  => $service,
            'method'   => $method,
            'priority' => $priority,
        ];
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
        $this->loadCommandListeners();

        $fileSystem = new Filesystem();
        $fileSystem->mkdir($cacheDir.'/ite_cron');

        $fileSystem->dumpFile($cacheDir.'/ite_cron/cron_listeners.meta', serialize($this->listeners));
    }

    /**
     * Load commands, annotated as CronCommands.
     */
    protected function loadCommandListeners()
    {
        $reader = new AnnotationReader();

        foreach ($this->kernel->getBundles() as $bundle) {

            if (!is_dir($dir = $bundle->getPath().'/Command')) {
                return;
            }

            $finder = new Finder();
            $finder->files()->name('*Command.php')->in($dir);

            $prefix = $bundle->getNamespace().'\\Command';

            foreach ($finder as $file) {
                $ns = $prefix;
                if ($relativePath = $file->getRelativePath()) {
                    $ns .= '\\'.strtr($relativePath, '/', '\\');
                }
                $class = $ns.'\\'.$file->getBasename('.php');
                $r     = new \ReflectionClass($class);
                if ($r->isSubclassOf('Symfony\\Component\\Console\\Command\\Command') && !$r->isAbstract(
                    ) && !$r->getConstructor()->getNumberOfRequiredParameters()
                ) {
                    /** @var CronCommand $annotation */
                    if ($annotation = $reader->getClassAnnotation($r, 'ITE\CronBundle\Annotation\CronCommand')) {
                        $this->addListener(
                            self::LISTENER_TYPE_COMMAND,
                            $annotation->pattern,
                            $r->newInstance()->getName(),
                            null,
                            $annotation->priority
                        );
                    }

                }
            }
        }
    }

}