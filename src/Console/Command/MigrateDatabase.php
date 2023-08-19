<?php

namespace JDS\Console\Command;

use Throwable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use JDS\Console\Command\AbstractCommand;

class MigrateDatabase extends AbstractCommand
{

	public function __construct(
		protected Connection $connection,
		protected string $migrationsPath
	) {
		// parent::__construct($connection, $migrationsPath);
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

			// create new schema to pass to migration files 
			$schema = new Schema();
			// create sql for any migrations which have not been run ..i.e. which are not in the database
			foreach ($migrationsToApply as $migration) {
				if (strpos($migration, '.php') === false) {
					$migration .= '.php';
				}

				// require the object
				$migrationObject = require $this->migrationsPath . '/' . $migration;

				// call up method
				$migrationObject->up($schema);

				// add migration to database
				$this->insertMigration($migration);
			}
			// execute the sql query
			$sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
			foreach ($sqlArray as $sql) {
				$this->connection->executeQuery($sql);
			}

			$this->connection->commit();

			return 0;
		} catch (Throwable $throwable) {
			$this->connection->rollBack();

			throw $throwable;
		}
	}

	private function insertMigration(string $migration): void
	{
		$sql = "INSERT INTO migrations (migration) VALUES (?)";

		$stmt = $this->connection->prepare($sql);

		$stmt->bindValue(1, $migration);

		$stmt->executeStatement();
	}
}
