<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArteveldeDatabaseUserCommand.
 *
 * Use:
 * $ php app/console artevelde:database:user
 *
 * @author Olivier Parent <olivier.parent@arteveldehs.be>
 * @copyright Copyright © 2015-2016, Artevelde University College Ghent
 */
class ArteveldeDatabaseUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('artevelde:database:user')
            ->setDescription('Creates a database user based on the configuration');
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
        $dbAdminUsername = $container->getParameter('database_admin_user');
        $dbAdminPassword = $container->getParameter('database_admin_password');

        // Add database user with all privileges on (nonexistent) database
        $sql = "GRANT ALL PRIVILEGES ON ${dbName}.* TO '${dbUsername}' IDENTIFIED BY '${dbPassword}'";
        $command = sprintf('MYSQL_PWD=%s mysql --user=%s --execute="%s"', $dbAdminPassword, $dbAdminUsername, $sql);
        exec($command);

        $output->writeln("Database user `${dbUsername}` created!");
    }
}
