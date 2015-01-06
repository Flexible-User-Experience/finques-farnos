<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141223180010 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_translation DROP FOREIGN KEY FK_7BBF0EE1232D562B');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E638E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, property_id INT DEFAULT NULL, text TEXT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2C9211FEE7A1254A (contact_id), INDEX IDX_2C9211FE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_property (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_40292763DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_slider (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_visit (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_856D2834549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_property_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_F9C11A21232D562B (object_id), UNIQUE INDEX lookup_image_property_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_slider_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_588A1C3D232D562B (object_id), UNIQUE INDEX lookup_image_slider_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FEE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FE549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE image_property ADD CONSTRAINT FK_40292763DA5256D FOREIGN KEY (image_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE property_visit ADD CONSTRAINT FK_856D2834549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE image_property_translation ADD CONSTRAINT FK_F9C11A21232D562B FOREIGN KEY (object_id) REFERENCES image_property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_slider_translation ADD CONSTRAINT FK_588A1C3D232D562B FOREIGN KEY (object_id) REFERENCES image_slider (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_translation');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE549213EC');
        $this->addSql('DROP INDEX IDX_8BF21CDE549213EC ON property');
        $this->addSql('ALTER TABLE property ADD square_meters INT DEFAULT NULL, ADD total_visits INT DEFAULT NULL, CHANGE property_id type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDEC54C8C93 ON property (type_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact_message DROP FOREIGN KEY FK_2C9211FEE7A1254A');
        $this->addSql('ALTER TABLE image_property_translation DROP FOREIGN KEY FK_F9C11A21232D562B');
        $this->addSql('ALTER TABLE image_slider_translation DROP FOREIGN KEY FK_588A1C3D232D562B');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, meta_title VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, meta_alt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, position INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C53D045F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, field VARCHAR(32) NOT NULL COLLATE utf8_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX lookup_image_unique_idx (locale, object_id, field), INDEX IDX_7BBF0EE1232D562B (object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3DA5256D FOREIGN KEY (image_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE image_translation ADD CONSTRAINT FK_7BBF0EE1232D562B FOREIGN KEY (object_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE image_property');
        $this->addSql('DROP TABLE image_slider');
        $this->addSql('DROP TABLE property_visit');
        $this->addSql('DROP TABLE image_property_translation');
        $this->addSql('DROP TABLE image_slider_translation');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEC54C8C93');
        $this->addSql('DROP INDEX IDX_8BF21CDEC54C8C93 ON property');
        $this->addSql('ALTER TABLE property ADD property_id INT DEFAULT NULL, DROP type_id, DROP square_meters, DROP total_visits');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE549213EC FOREIGN KEY (property_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE549213EC ON property (property_id)');
    }
}
