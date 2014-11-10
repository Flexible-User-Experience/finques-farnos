<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141110171548 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('CREATE TABLE image (id INTEGER NOT NULL, image_name VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INTEGER NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE image_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7BBF0EE1232D562B ON image_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_image_unique_idx ON image_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('DROP INDEX lookup_museum_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3F20704232D562B FOREIGN KEY (object_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_museum_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX lookup_type_unique_idx');
        $this->addSql('DROP INDEX IDX_FF7092FE232D562B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_translation AS SELECT id, object_id, locale, field, content FROM type_translation');
        $this->addSql('DROP TABLE type_translation');
        $this->addSql('CREATE TABLE type_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_FF7092FE232D562B FOREIGN KEY (object_id) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO type_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__type_translation');
        $this->addSql('DROP TABLE __temp__type_translation');
        $this->addSql('CREATE UNIQUE INDEX lookup_type_unique_idx ON type_translation (locale, object_id, field)');
        $this->addSql('CREATE INDEX IDX_FF7092FE232D562B ON type_translation (object_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('DROP INDEX lookup_category_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_category_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX IDX_FF7092FE232D562B');
        $this->addSql('DROP INDEX lookup_type_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_translation AS SELECT id, object_id, locale, field, content FROM type_translation');
        $this->addSql('DROP TABLE type_translation');
        $this->addSql('CREATE TABLE type_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO type_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__type_translation');
        $this->addSql('DROP TABLE __temp__type_translation');
        $this->addSql('CREATE INDEX IDX_FF7092FE232D562B ON type_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_type_unique_idx ON type_translation (locale, object_id, field)');
    }
}
