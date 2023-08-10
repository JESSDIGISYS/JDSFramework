<?php

namespace JDS\Console;

use JDS\Exceptions\ConsoleException;
use Psr\Container\ContainerInterface;

class Application
{

	public function __construct(
		private ContainerInterface $container
	)
	{

	}

	public function run(): int
	{
		// use environment variables to obtain the command name
		$argv = $_SERVER['argv'];
		$commandName = $argv[1] ?? null;

		// throw an exception if no command name is provided
		if (!$commandName) {
			throw new ConsoleException('A command name must be provided');
		}
		// use command name to obtain a command object from the container
		$command = $this->container->get($commandName);
		dd($command);
		
		// parse variables to obtain options and args

		// execute the command, returning the status code

		// return the status code
		return 0;
	}
}