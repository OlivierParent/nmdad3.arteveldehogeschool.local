<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDatabaseBackupCommand.
 *
 * Use:
 * $ php app/console artevelde:database:backup
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class ArteveldeDatabaseBackupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:database:backup')
            ->setDescription('Dumps database to SQL file for backup');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        // Get variables from `app/config/parameters.yml`
        $dbName = $container->getParameter('database_name');
        $dbUsername = $container->getParameter('database_user');
        $dbPassword = $container->getParameter('database_password');
        $dbDumpPath = $container->getParameter('database_dump_path');

        // Create folder(s)
        $command = "mkdir -p ${dbDumpPath}";
        exec($command);

        // Create SQL database dump
        $command = "MYSQL_PWD=${dbPassword} mysqldump --user=${dbUsername} --databases ${dbName} > ${dbDumpPath}/latest.sql";
        exec($command);

        // Gzip and timestamp created SQL database dump
        $command = "gzip -cr ${dbDumpPath}/latest.sql > ${dbDumpPath}/\$(date +\"%Y-%m-%d_%H%M%S\").sql.gz";
        exec($command);

        $output->writeln("Backup for database `${dbName}` stored!");
    }
}
