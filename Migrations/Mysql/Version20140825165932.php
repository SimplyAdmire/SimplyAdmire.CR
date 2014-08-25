<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 *
 */
class Version20140825165932 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("CREATE TABLE simplyadmire_cr_domain_model_event (persistence_object_identifier VARCHAR(40) NOT NULL, nodeidentifier VARCHAR(255) NOT NULL, eventtype VARCHAR(255) NOT NULL, timestamp DOUBLE PRECISION NOT NULL, workspace VARCHAR(255) NOT NULL, dimensionshash VARCHAR(255) NOT NULL, eventobject LONGTEXT NOT NULL, INDEX nodeidentifier_workspace_dimensionshash (nodeidentifier, workspace, dimensionshash), PRIMARY KEY(persistence_object_identifier)) ENGINE = InnoDB");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("DROP TABLE simplyadmire_cr_domain_model_event");
	}
}