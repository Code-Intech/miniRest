<?php

namespace MiniRest\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunServeCommand extends Command
{
    protected function configure()
    {
        $this->setName('serve')
            ->setDescription('Start the PHP Server')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host address', 'localhost')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port number', 8000);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host');
        $port = $input->getOption('port');

        $output->writeln("Server started at http://$host:$port");
        $output->writeln("php -S $host:$port");
        passthru("php -S $host:$port");
        return 1;
    }
}