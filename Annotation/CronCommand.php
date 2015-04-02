<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 18:33
 */

namespace ITE\CronBundle\Annotation;

/**
 * Class CronCommand
 *
 * @package ITE\CronBundle\Annotation
 * @Annotation()
 */
class CronCommand
{
    /**
     * @var string
     */
    public $pattern;

    /**
     * @var int
     */
    public $priority = 0;

    /**
     * @var string
     */
    public $parameters = '';
}