<?php

namespace JDS\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;

abstract class AbstractCommand implements CommandInterface
{

	public function __construct(
		protected Connection $connection,
		protected string $migrationsPath
	) {
	}

	protected function createMigrationsTable(): void
	{
		$schemaManager = $this->connection->createSchemaManager();

		if (!$schemaManager->tablesExist('migrations')) {
			$schema = new Schema();
			$table = $schema->createTable('migrations');
			$table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
			$table->addColumn('migration', Types::STRING);
			$table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
			$table->setPrimaryKey(['id']);
			$table->addIndex(['migration'], 'migration');
			$sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
			$this->connection->executeQuery($sqlArray[0]);
			echo 'Migrations table created' . PHP_EOL;
		}
	}

	protected function getAppliedMigrations(): array
	{
		$sql = "SELECT migration FROM migrations";
		$appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();

		return $appliedMigrations;
	}

	protected function getMigrationFiles(): array
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
