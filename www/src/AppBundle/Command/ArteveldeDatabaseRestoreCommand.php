<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDatabaseRestoreCommand.
 *
 * Use:
 * $ php app/console artevelde:database:restore
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class ArteveldeDatabaseRestoreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:database:restore')
            ->setDescription('Restores database from latest SQL dump');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $dbName = $container->getParameter('database_name');
        $dbUser = $container->getParameter('database_user');
        $dbPassword = $container->getParameter('database_password');
        $dbDumpPath = $container->getParameter('database_dump_path');

        $command = "MYSQL_PWD=${dbPassword} mysqldump --user=${dbUser} ${dbName} < ${dbDumpPath}/latest.sql";
        exec($command);

        $output->writeln("Backup for database `${dbName}` restored!");
    }
}
