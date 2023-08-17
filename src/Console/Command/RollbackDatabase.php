<?php

namespace JDS\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Throwable;

class RollbackDatabase extends AbstractCommand
{

	public function __construct(
		protected Connection $connection, 
		protected string $migrationsPath
		)
	{
		// parent::__construct($connection, $migrationsPath);
	}

	private string $name = 'database:migrations:rollback';

	public function execute(array $params = []): int
	{
		try {
			// create a migrations table sql if table not already in existence
			$this->createMigrationsTable();
			dd('After Migrations Table Create Command');
			// start a transaction
			$this->connection->beginTransaction();

			// get $appliedMigrations which are already in the database.migrations table
			$appliedMigrations = $this->getMigrations();

			// create new schema to pass to migration files 
			$schema = $this->connection->createSchemaManager()->introspectSchema();
			
			// create sql for any migrations which have not been run ..i.e. which are not in the database
			foreach ($appliedMigrations as $migration) {
				if (strpos($migration, '.php') === false) {
					$migration .= '.php';
				}
				// require the object
				$migrationObject = require $this->migrationsPath . '/' . $migration;
				// call up method
				$migrationObject->down($schema);

				// add migration to database
				$this->removeMigration($migration);

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

	private function removeMigration(string $migration): void
	{
		$sql = "DELETE FROM migrations WHERE migration = ?";

		$stmt = $this->connection->prepare($sql);

		$stmt->bindValue(1, $migration);

		$stmt->executeStatement();
	}

	private function getMigrations(): array
	{
		$sql = "SELECT migration FROM migrations ORDER BY migration DESC";
		$appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();

		return $appliedMigrations;
	}

}
