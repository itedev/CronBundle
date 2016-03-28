<?php

namespace ITE\CronBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CronRunCommand
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CronRunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('ite:cron:run')->setDescription('Run all defined cron listeners and commands.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cronManager = $this->getContainer()->get('ite_cron.manager');

        $cronManager->runCron($this->getApplication(), $input, $output);
    }
}
