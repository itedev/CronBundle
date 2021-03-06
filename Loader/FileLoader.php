<?php

namespace ITE\CronBundle\Loader;

use Symfony\Component\Config\Loader\FileLoader as BaseLoader;

/**
 * Class FileLoader
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FileLoader extends BaseLoader 
{
    /**
     * @param mixed $resource
     * @param null  $type
     * @return mixed
     */
    public function load($resource, $type = null)
    {
        $filePath = $this->locator->locate($resource);

        return unserialize(file_get_contents($filePath));
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed       $resource A resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource);
    }
}
