<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ArteveldeDatabaseResetCommand.
 *
 * Use:
 * $ php app/console artevelde:database:reset
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
            ->addOption('seed', null, InputOption::VALUE_NONE, 'Loads Doctrine fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $application = $this->getApplication();

        $commands = [
            'doctrine:database:drop' => ['--force' => true],
            'artevelde:database:init' => null,
        ];

        if ($input->getOption('seed')) {
            $commands['artevelde:database:init'] = ['--seed' => true];
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
