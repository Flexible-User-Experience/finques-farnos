<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141110183644 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('CREATE TABLE property (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_old DOUBLE PRECISION DEFAULT NULL, rooms INTEGER DEFAULT NULL, bathrooms INTEGER DEFAULT NULL, offer_discount BOOLEAN NOT NULL, offer_special BOOLEAN NOT NULL, energy_class INTEGER DEFAULT NULL, gps_longitude DOUBLE PRECISION NOT NULL, gps_latitude DOUBLE PRECISION NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDE5E237E06 ON property (name)');
        $this->addSql('CREATE TABLE property_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B0C8559232D562B ON property_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_property_unique_idx ON property_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX lookup_museum_unique_idx');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3F20704232D562B FOREIGN KEY (object_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE UNIQUE INDEX lookup_museum_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('DROP INDEX lookup_image_unique_idx');
        $this->addSql('DROP INDEX IDX_7BBF0EE1232D562B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image_translation AS SELECT id, object_id, locale, field, content FROM image_translation');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('CREATE TABLE image_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_7BBF0EE1232D562B FOREIGN KEY (object_id) REFERENCES image (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__image_translation');
        $this->addSql('DROP TABLE __temp__image_translation');
        $this->addSql('CREATE UNIQUE INDEX lookup_image_unique_idx ON image_translation (locale, object_id, field)');
        $this->addSql('CREATE INDEX IDX_7BBF0EE1232D562B ON image_translation (object_id)');
        $this->addSql('DROP INDEX IDX_FF7092FE232D562B');
        $this->addSql('DROP INDEX lookup_type_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type_translation AS SELECT id, object_id, locale, field, content FROM type_translation');
        $this->addSql('DROP TABLE type_translation');
        $this->addSql('CREATE TABLE type_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_FF7092FE232D562B FOREIGN KEY (object_id) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO type_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__type_translation');
        $this->addSql('DROP TABLE __temp__type_translation');
        $this->addSql('CREATE INDEX IDX_FF7092FE232D562B ON type_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_type_unique_idx ON type_translation (locale, object_id, field)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_translation');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('DROP INDEX lookup_category_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_category_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX IDX_7BBF0EE1232D562B');
        $this->addSql('DROP INDEX lookup_image_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image_translation AS SELECT id, object_id, locale, field, content FROM image_translation');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('CREATE TABLE image_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO image_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__image_translation');
        $this->addSql('DROP TABLE __temp__image_translation');
        $this->addSql('CREATE INDEX IDX_7BBF0EE1232D562B ON image_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_image_unique_idx ON image_translation (locale, object_id, field)');
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
