<?php

namespace ITE\CronBundle;

use ITE\CronBundle\DependencyInjection\Compiler\CronListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

class ITECronBundle extends Bundle
{

    /**
     * @var Kernel
     */
    private $kernel;

    function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CronListenerPass($this->kernel));
    }

}
