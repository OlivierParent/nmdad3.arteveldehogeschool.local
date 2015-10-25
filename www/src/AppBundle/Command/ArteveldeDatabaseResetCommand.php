<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDatabaseResetCommand.
 *
 * Use:
 * $ php app/console artevelde:database:reset --migrate --seed
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class ArteveldeDatabaseResetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:database:reset')
            ->setDescription('Drops database and runs artevelde:database:init')
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
            'doctrine:database:drop' => ['--force' => true],
            'artevelde:database:init' => null,
        ];

        if ($input->getOption('migrate') || $input->getOption('seed')) {
            $options = [];

            if ($input->getOption('migrate')) {
                $options['--migrate'] = true;
            }

            if ($input->getOption('seed')) {
                $options['--seed'] = true;
            }

            $commands['artevelde:database:init'] = $options;
        }

        foreach ($commands as $commandName => $commandParameters) {
            $parameters = [
                'command' => $commandName,
            ];
            if (is_array($commandParameters)) {
                foreach ($commandParameters as $commandParameter => $value) {
                    $parameters[$commandParameter] = $value;
                }
            }
            $commandInput = new ArrayInput($parameters);

            $application
                ->find($commandName)
                ->run($commandInput, $output);
        }
    }
}
