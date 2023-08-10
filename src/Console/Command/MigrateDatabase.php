<?php

namespace JDS\Console\Command;

class MigrateDatabase implements CommandInterface
{

	private string $name = 'database:migrations:migrate';

	public function execute(array $params = []) : int
	{
		dd($params);
		echo 'Executing MigrateDatabase command' . PHP_EOL;

		return 0;
	}
}



