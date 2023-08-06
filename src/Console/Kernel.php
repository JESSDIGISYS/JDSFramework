<?php

namespace JDS\Console;

final class Kernel {

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
		$commandFiles = scandir(__DIR__ . '/Command');
		dd($commandFiles);
		
		// loop over all files in the commands folder

			// get the Command class name..using psr4 this will be same as filename

			// if it is a subclass of CommandInterface

				// add to the container, using the name as the ID e.g. $container->add('database:migrations:migrate', MigrateDatabase::class)

		// === register all user-defined commands (@todo) ===

	}
}