<?php
/**
 * This file is created by sam0delkin (t.samodelkin@gmail.com).
 * IT-Excellence (http://itedev.com)
 * Date: 31.03.2015
 * Time: 16:58
 */

namespace ITE\CronBundle\Cron;


use Cron\Schedule\CrontabSchedule;
use ITE\CronBundle\Cron\Reference\CommandReference;
use ITE\CronBundle\Cron\Reference\ListenerReference;
use ITE\CronBundle\Cron\Reference\ReferenceInterface;
use ITE\CronBundle\Event\CronEvent;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var ListenerReference[]
     */
    protected $listeners = [];

    /**
     * @var CommandReference[]
     */
    protected $commands = [];

    /**
     * @param LoaderInterface $loader
     * @param string          $cacheDir
     */
    public function __construct(LoaderInterface $loader, $cacheDir)
    {
        $this->loader   = $loader;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Add listener to manager
     *
     * @param ListenerReference $reference
     */
    public function addListener(ListenerReference $reference)
    {
        $this->listeners[] = $reference;
    }

    /**
     * Add command to manager
     *
     * @param CommandReference $reference
     */
    public function addCommand(CommandReference $reference)
    {
        $this->commands[] = $reference;
    }

    /**
     * Main entry point for run cron on all listeners/commands.
     *
     * @param Application     $application
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function runCron(Application $application, InputInterface $input, OutputInterface $output)
    {
        $this->sortReferences($this->commands);
        $this->sortReferences($this->listeners);

        $now = new \DateTime();

        $output->writeln('Start of running commands.');

        foreach ($this->listeners as $listener) {
            if ($listener->getSchedule()->valid($now)) {
                $output->writeln(
                    sprintf('Running "%s:%s" listener', get_class($listener->getService()), $listener->getMethod())
                );
                $this->callListener($listener, $input, $output);
                $output->writeln('Done.');
            }
        }

        foreach ($this->commands as $command) {
            if ($command->getSchedule()->valid($now)) {
                $output->writeln(
                    sprintf('Running "%s" command', $command->getName())
                );
                $this->callCommand($application, $input, $output, $command);
                $output->writeln('Done.');
            }
        }

    }

    /**
     * @return Reference\ListenerReference[]
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @return Reference\CommandReference[]
     */
    public function getCommands()
    {
        return $this->commands;
    }


    /**
     * Sort references by priority
     *
     * @param ReferenceInterface[] $references
     */
    protected function sortReferences(&$references)
    {
        usort(
            $references,
            function (ReferenceInterface $a, ReferenceInterface $b) {
                return $a->getPriority() > $b->getPriority() ? 1 : -1;
            }
        );
    }

    /**
     * Call service listener.
     *
     * @param ListenerReference $reference
     * @param InputInterface    $input
     * @param OutputInterface   $output
     */
    protected function callListener(ListenerReference $reference, InputInterface $input, OutputInterface $output)
    {
        $event = new CronEvent($input, $output);
        call_user_func([$reference->getService(), $reference->getMethod()], $event);
    }

    /**
     * Call command.
     *
     * @param Application      $application
     * @param InputInterface   $input
     * @param OutputInterface  $output
     * @param CommandReference $reference
     * @throws \Exception
     */
    protected function callCommand(
        Application $application,
        InputInterface $input,
        OutputInterface $output,
        CommandReference $reference
    ) {
        $command = $application->get($reference->getName());
        $command->run($input, $output);
    }
}