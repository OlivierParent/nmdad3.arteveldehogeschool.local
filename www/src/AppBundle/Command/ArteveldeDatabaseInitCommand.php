<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDatabaseInitCommand.
 *
 * Use:
 * $ php app/console artevelde:database:init --migrate --seed
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class ArteveldeDatabaseInitCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:database:init')
            ->setDescription('Creates database user, creates database and schema')
            ->addOption('migrate', null, InputOption::VALUE_NONE, 'Migrates Doctrine Migrations')
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Loads Doctrine Fixtures');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();

        $commands = [
            'artevelde:database:user',
            'doctrine:database:create',
            'doctrine:schema:create',
        ];

        if ($input->getOption('migrate')) {
            $commands[] = 'doctrine:migrations:migrate';
        }

        if ($input->getOption('seed')) {
            $commands[] = 'doctrine:fixtures:load';
        }

        foreach ($commands as $commandName) {
            $parameters = [
                'command' => $commandName,
            ];
            $commandInput = new ArrayInput($parameters);

            if (in_array($commandName, ['doctrine:fixtures:load', 'doctrine:migrations:migrate'])) {
                $commandInput->setInteractive(false);
            }

            $application
                ->find($commandName)
                ->run($commandInput, $output);
        }
    }
}
