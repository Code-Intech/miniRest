<?php

namespace MiniRest\Console;

use \Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends SymfonyCommand
{
    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}