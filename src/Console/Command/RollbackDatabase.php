<?php

namespace JDS\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;
use Throwable;

class RollbackDatabase implements CommandInterface
{

	public function __construct(
		private Connection $connection,
		private string $migrationsPath
		)
	{
	}

	private string $name = 'database:migrations:rollback';

	public function execute(array $params = []): int
	{
		try {
			// create a migrations table sql if table not already in existence
			$this->createMigrationsTable();

			// start a transaction
			$this->connection->beginTransaction();

			// get $appliedMigrations which are already in the database.migrations table
			$appliedMigrations = $this->getAppliedMigrations();

			// create new schema to pass to migration files 
			$schema = new Schema();
			
			// create sql for any migrations which have not been run ..i.e. which are not in the database
			foreach ($appliedMigrations as $migration) {
				dd(substr($migration, strpos($migration, '.php')));

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

	private function insertMigration(string $migration): void
	{
		$sql = "INSERT INTO migrations (migration) VALUES (?)";

		$stmt = $this->connection->prepare($sql);

		$stmt->bindValue(1, $migration);

		$stmt->executeStatement();
	}

	private function removeMigration(string $migration): void
	{
		$sql = "DELETE FROM migrations WHERE migration = ?";

		$stmt = $this->connection->prepare($sql);

		$stmt->bindValue(1, $migration);

		$stmt->executeStatement();
	}
}
