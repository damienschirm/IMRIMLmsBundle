<?php

namespace IMRIM\Bundle\LmsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegisterFromCsvCommand extends ContainerAwareCommand {

    private $delim = ','; // CSV delimiter

    /**
     * Defines the command behaviour. 
     */
    protected function configure() {
        parent::configure();
        $this->setName('register:csv') // command : app/console register:csv
                ->setDescription('import csv file')
                ->addArgument('file', InputArgument::REQUIRED, 'CSV File to parsing')
                ->addArgument('role', InputArgument::REQUIRED, 'Role of the user to create')
                ->addOption('force');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException when this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $fileName = $input->getArgument('file');
        $roleName = $input->getArgument('role');
	$force = $input->getOption('force');
	$userManager = $this->getContainer()->get('imrim_lms.user_manager');
	
	$exitCode = $userManager->csvUserImport($fileName, $roleName, $force);

        $logger = $this->getContainer()->get('logger');
 
	    
        foreach ($logger->getLogs() as $log)
        {
            if($log['priority'] == 200) { // print only info log
                $output->writeln(sprintf('<info>%s</info>', $log['message']));
            } elseif($log['priority'] == 400) { // print only info log
                $output->writeln(sprintf('<error>%s</error>', $log['message']));
            }
        }
	return $exitCode;
    }

}
