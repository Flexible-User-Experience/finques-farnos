<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141109203657 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_museum_unique_idx ON category_translation (locale, object_id, field)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('DROP TABLE category_translation');
    }
}
