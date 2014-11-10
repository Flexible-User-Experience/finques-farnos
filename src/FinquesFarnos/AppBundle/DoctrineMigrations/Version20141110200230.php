<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141110200230 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');
        
        $this->addSql('CREATE TABLE property_category (property_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(property_id, category_id))');
        $this->addSql('CREATE INDEX IDX_58CB2D85549213EC ON property_category (property_id)');
        $this->addSql('CREATE INDEX IDX_58CB2D8512469DE2 ON property_category (category_id)');
        $this->addSql('DROP INDEX IDX_C53D045F3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, image_name VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INTEGER NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_C53D045F3DA5256D FOREIGN KEY (image_id) REFERENCES property (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image (id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at) SELECT id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F3DA5256D ON image (image_id)');
        $this->addSql('DROP INDEX UNIQ_8BF21CDE5E237E06');
        $this->addSql('DROP INDEX IDX_8BF21CDE549213EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__property AS SELECT id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at FROM property');
        $this->addSql('DROP TABLE property');
        $this->addSql('CREATE TABLE property (id INTEGER NOT NULL, property_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_old DOUBLE PRECISION DEFAULT NULL, rooms INTEGER DEFAULT NULL, bathrooms INTEGER DEFAULT NULL, offer_discount BOOLEAN NOT NULL, offer_special BOOLEAN NOT NULL, energy_class INTEGER DEFAULT NULL, gps_longitude DOUBLE PRECISION NOT NULL, gps_latitude DOUBLE PRECISION NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_8BF21CDE549213EC FOREIGN KEY (property_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO property (id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at) SELECT id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at FROM __temp__property');
        $this->addSql('DROP TABLE __temp__property');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDE5E237E06 ON property (name)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE549213EC ON property (property_id)');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('DROP INDEX lookup_museum_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3F20704232D562B FOREIGN KEY (object_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_museum_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX IDX_7BBF0EE1232D562B');
        $this->addSql('DROP INDEX lookup_image_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image_translation AS SELECT id, object_id, locale, field, content FROM image_translation');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('CREATE TABLE image_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_7BBF0EE1232D562B FOREIGN KEY (object_id) REFERENCES image (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO image_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__image_translation');
        $this->addSql('DROP TABLE __temp__image_translation');
        $this->addSql('CREATE INDEX IDX_7BBF0EE1232D562B ON image_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_image_unique_idx ON image_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX lookup_property_unique_idx');
        $this->addSql('DROP INDEX IDX_B0C8559232D562B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__property_translation AS SELECT id, object_id, locale, field, content FROM property_translation');
        $this->addSql('DROP TABLE property_translation');
        $this->addSql('CREATE TABLE property_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_B0C8559232D562B FOREIGN KEY (object_id) REFERENCES property (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO property_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__property_translation');
        $this->addSql('DROP TABLE __temp__property_translation');
        $this->addSql('CREATE UNIQUE INDEX lookup_property_unique_idx ON property_translation (locale, object_id, field)');
        $this->addSql('CREATE INDEX IDX_B0C8559232D562B ON property_translation (object_id)');
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
        
        $this->addSql('DROP TABLE property_category');
        $this->addSql('DROP INDEX IDX_3F20704232D562B');
        $this->addSql('DROP INDEX lookup_category_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__category_translation AS SELECT id, object_id, locale, field, content FROM category_translation');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('CREATE TABLE category_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO category_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__category_translation');
        $this->addSql('DROP TABLE __temp__category_translation');
        $this->addSql('CREATE INDEX IDX_3F20704232D562B ON category_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_category_unique_idx ON category_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX IDX_C53D045F3DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image AS SELECT id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at FROM image');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE image (id INTEGER NOT NULL, image_id INTEGER DEFAULT NULL, image_name VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INTEGER NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO image (id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at) SELECT id, image_id, image_name, meta_title, meta_alt, position, enabled, created_at, updated_at, deleted_at FROM __temp__image');
        $this->addSql('DROP TABLE __temp__image');
        $this->addSql('CREATE INDEX IDX_C53D045F3DA5256D ON image (image_id)');
        $this->addSql('DROP INDEX IDX_7BBF0EE1232D562B');
        $this->addSql('DROP INDEX lookup_image_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__image_translation AS SELECT id, object_id, locale, field, content FROM image_translation');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('CREATE TABLE image_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO image_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__image_translation');
        $this->addSql('DROP TABLE __temp__image_translation');
        $this->addSql('CREATE INDEX IDX_7BBF0EE1232D562B ON image_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_image_unique_idx ON image_translation (locale, object_id, field)');
        $this->addSql('DROP INDEX UNIQ_8BF21CDE5E237E06');
        $this->addSql('DROP INDEX IDX_8BF21CDE549213EC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__property AS SELECT id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at FROM property');
        $this->addSql('DROP TABLE property');
        $this->addSql('CREATE TABLE property (id INTEGER NOT NULL, property_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_old DOUBLE PRECISION DEFAULT NULL, rooms INTEGER DEFAULT NULL, bathrooms INTEGER DEFAULT NULL, offer_discount BOOLEAN NOT NULL, offer_special BOOLEAN NOT NULL, energy_class INTEGER DEFAULT NULL, gps_longitude DOUBLE PRECISION NOT NULL, gps_latitude DOUBLE PRECISION NOT NULL, enabled BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO property (id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at) SELECT id, property_id, name, name_slug, description, price, price_old, rooms, bathrooms, offer_discount, offer_special, energy_class, gps_longitude, gps_latitude, enabled, created_at, updated_at, deleted_at FROM __temp__property');
        $this->addSql('DROP TABLE __temp__property');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BF21CDE5E237E06 ON property (name)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE549213EC ON property (property_id)');
        $this->addSql('DROP INDEX IDX_B0C8559232D562B');
        $this->addSql('DROP INDEX lookup_property_unique_idx');
        $this->addSql('CREATE TEMPORARY TABLE __temp__property_translation AS SELECT id, object_id, locale, field, content FROM property_translation');
        $this->addSql('DROP TABLE property_translation');
        $this->addSql('CREATE TABLE property_translation (id INTEGER NOT NULL, object_id INTEGER DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO property_translation (id, object_id, locale, field, content) SELECT id, object_id, locale, field, content FROM __temp__property_translation');
        $this->addSql('DROP TABLE __temp__property_translation');
        $this->addSql('CREATE INDEX IDX_B0C8559232D562B ON property_translation (object_id)');
        $this->addSql('CREATE UNIQUE INDEX lookup_property_unique_idx ON property_translation (locale, object_id, field)');
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
