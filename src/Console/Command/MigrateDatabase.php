<?php

namespace JDS\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;
use Throwable;

class MigrateDatabase implements CommandInterface
{

	public function __construct(
		private Connection $connection,
		private string $migrationsPath
		)
	{
	}

	private string $name = 'database:migrations:migrate';

	public function execute(array $params = []): int
	{

		try {
			// create a migrations table sql if table not already in existence
			$this->createMigrationsTable();

			// start a transaction
			$this->connection->beginTransaction();

			// get $appliedMigrations which are already in the database.migrations table
			$appliedMigrations = $this->getAppliedMigrations();

			// get the $migrationFiles from the migrations folder
			$migrationFiles = $this->getMigrationFiles();

			// get the migrations to apply. i.e. they are in $migrationFiles but not in $appliedMigrations
			$migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

			// create sql for any migrations which have not been run ..i.e. which are not in the database
			foreach ($migrationsToApply as $migration) {
				
				// require the object
				$migrationObject = require $this->migrationsPath . '/' . $migration;
				dd($migrationObject);
				
				// call up method

				// add migration to database

			}
			// add migration to database

			// execute the sql query
			$this->connection->commit();



			echo 'Executing MigrateDatabase command' . PHP_EOL;

			return 0;
		} catch (Throwable $throwable) {
			$this->connection->rollBack();

			throw $throwable;
		}
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

	private function getAppliedMigrations(): array
	{
		$sql = "SELECT migration FROM migrations";
		$appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();
		
		return $appliedMigrations;
	}

	private function getMigrationFiles(): array
	{
		$filteredFiles = array_diff(scandir($this->migrationsPath), ['..', '.']);

		// Garys way: 
		// $migrationFiles = scandir($this->migrationsPath);
		// $filteredFiles = array_filter($migrationFiles, function($file) { 
		// 	return !in_array($file, ['.', '..']); 
		// });
		
		return $filteredFiles;
		
	}
}
