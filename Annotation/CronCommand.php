<?php

namespace ITE\CronBundle\Annotation;

/**
 * Class CronCommand
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
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
