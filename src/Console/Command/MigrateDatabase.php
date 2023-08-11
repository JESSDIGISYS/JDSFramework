<?php

namespace JDS\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;

class MigrateDatabase implements CommandInterface
{

	public function __construct(private Connection $connection)
	{
	}

	private string $name = 'database:migrations:migrate';

	public function execute(array $params = []): int
	{

		// create a migrations table sql if table not already in existence
		$this->createMigrationsTable();

		// get $appliedMigrations which are already in the database.migrations table

		// get the $migrationFiles from the migrations folder

		// get the migrations to apply. i.e. they are in $migrationFiles but not in $appliedMigrations

		// create sql for any migrations which have not been run ..i.e. which are not in the database

		// add migration to database

		// execute the sql query




		echo 'Executing MigrateDatabase command' . PHP_EOL;

		return 0;
	}

	private function createMigrationsTable(): void
	{
		$schemaManager = $this->connection->createSchemaManager();

		if (!$schemaManager->tablesExist('migrations')) {
			$schema = new Schema();
			$table = $schema->createTable('migrations');
			$table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
			$table->addColumn('migration', Types::STRING);
			$table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
			$table->setPrimaryKey(['id']);
			$sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
			$this->connection->executeQuery($sqlArray[0]);
			echo 'Migrations table created' . PHP_EOL;
		}

	}
}
