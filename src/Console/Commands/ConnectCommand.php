<?php

namespace Yoke\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class ConnectCommand extends BaseCommand
{
    /**
     * @var string Command name.
     */
    protected $name = 'connect';

    /**
     * @var string Command description.
     */
    protected $description = 'Connect into a saved configuration';

    /**
     * @var array Command arguments.
     */
    protected $arguments = [
        ['alias', InputArgument::REQUIRED, 'Connection Alias'],
    ];

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     */
    protected function fire(InputInterface $input)
    {
        // Gets the desired connection alias.
        $alias = $this->argument('alias');

        // Finds the store server connection using the provided alias.
        $server = $this->manager->getServer($alias);

        if ($server->authenticationMethod === 'password') {
            $showPassword = $this->askConfirmation('Show password for this server?');

            if ($showPassword) {
                $this->writeln($server->passwordHelper());
            }
        }

        // Write the console line to be executed on the bash side of
        // the string. sometimes it will contain a password
        // for usage while authenticating
        $this->writelnPlain($server->connectionString());
    }
}
