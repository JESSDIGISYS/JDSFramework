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

		// parse variables to obtain options and args
		$args = array_slice($argv, 2);
		$options = $this->parseOptions($args);

		// execute the command, returning the status code
		$status = $command->execute($options);
		
		// return the status code
		return $status;
	}

	private function parseOptions(array $args) : array
	{
		$options = [];

		foreach ($args as $arg) {
			if (str_starts_with($arg, '--')) {
				// this is an option
				$option = explode('=', substr($arg, 2));
				$options[$option[0]] = $option[1] ?? true;
			
			}
		}
		return $options;
	}
}
