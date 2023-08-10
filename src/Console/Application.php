<?php

namespace JDS\Console;

class Application 
{

	public function run(): int
	{
		// use environment variables to obtain the command name
		$argv = $_SERVER['argv'];
		$commandName = $argv[1] ?? null;
		dd($commandName);

		// throw an exception if no command name is provided

		// use command name to obtain a command object from the container

		// parse variables to obtain options and args

		// execute the command, returning the status code

		// return the status code
		return 0;
	}
}