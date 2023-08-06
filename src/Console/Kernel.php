<?php

namespace JDS\Console;

use JDS\Console\Command\CommandInterface;
use Psr\Container\ContainerInterface;

final class Kernel {

		public function __construct(private ContainerInterface $container)
		{

		}

	public function handle(): int 
	{

		// register commands with the container
		$this->registerCommands();

		// run the console application, returning a status code

		// return the status code
		return 0;
	}

	private function registerCommands(): void
	{

		// === register All built-in Commands ===
		// get all files in the Commands dir
		$commandFiles = new \DirectoryIterator(__DIR__ . '/Command');

		$namespace = $this->container->get('base-commands-namespace');
				
		// loop over all files in the commands folder
		foreach ($commandFiles as $commandFile) {

			// check if file is a dir
			if (!$commandFile->isFile()) {
				continue;
			}

			// get the Command class name..using psr4 this will be same as filename
			$command = $namespace.pathinfo($commandFile, PATHINFO_FILENAME);
			// if it is a subclass of CommandInterface
			if (is_subclass_of($command, CommandInterface::class))
			{

				// add to the container, using the name as the ID e.g. $container->add('database:migrations:migrate', MigrateDatabase::class)
				$commandName = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();
				dd($commandName);
			}
		}
		
		
		// === register all user-defined commands (@todo) ===

	}
}