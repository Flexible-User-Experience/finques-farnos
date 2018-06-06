<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180605102542 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_583D1F3E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(1000) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C560D76192FC23A8 (username_canonical), UNIQUE INDEX UNIQ_C560D761A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8CDE57295E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2D5B02345E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C62E638E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_visit (id INT AUTO_INCREMENT NOT NULL, property_id INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_856D2834549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, property_id INT DEFAULT NULL, text TEXT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_2C9211FEE7A1254A (contact_id), INDEX IDX_2C9211FE549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, dni VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_81398E09E7927C74 (email), UNIQUE INDEX UNIQ_81398E097F8F253B (dni), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, city_id INT DEFAULT NULL, reference VARCHAR(16) NOT NULL, name VARCHAR(255) NOT NULL, name_slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, square_meters INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, price_old DOUBLE PRECISION DEFAULT NULL, rooms INT DEFAULT NULL, bathrooms INT DEFAULT NULL, hide_price TINYINT(1) NOT NULL, offer_discount TINYINT(1) NOT NULL, offer_special TINYINT(1) NOT NULL, show_in_homepage TINYINT(1) NOT NULL, show_price_only_with_numbers TINYINT(1) NOT NULL, reserved TINYINT(1) NOT NULL, sold TINYINT(1) NOT NULL, sold_at DATETIME DEFAULT NULL, energy_class INT DEFAULT NULL, show_map_type INT NOT NULL, gps_longitude DOUBLE PRECISION NOT NULL, gps_latitude DOUBLE PRECISION NOT NULL, radius INT DEFAULT NULL, total_visits INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8BF21CDEAEA34913 (reference), INDEX IDX_8BF21CDEC54C8C93 (type_id), INDEX IDX_8BF21CDE9395C3F3 (customer_id), INDEX IDX_8BF21CDE8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_category (property_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_58CB2D85549213EC (property_id), INDEX IDX_58CB2D8512469DE2 (category_id), PRIMARY KEY(property_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_slider (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, link VARCHAR(255) DEFAULT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_3F20704232D562B (object_id), UNIQUE INDEX lookup_category_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_B0C8559232D562B (object_id), UNIQUE INDEX lookup_property_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_FF7092FE232D562B (object_id), UNIQUE INDEX lookup_type_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_property_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_F9C11A21232D562B (object_id), UNIQUE INDEX lookup_image_property_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_slider_translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_588A1C3D232D562B (object_id), UNIQUE INDEX lookup_image_slider_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_property (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, meta_title VARCHAR(255) DEFAULT NULL, meta_alt VARCHAR(255) DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_40292763DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_visit ADD CONSTRAINT FK_856D2834549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FEE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE contact_message ADD CONSTRAINT FK_2C9211FE549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE property_category ADD CONSTRAINT FK_58CB2D85549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_category ADD CONSTRAINT FK_58CB2D8512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_translation ADD CONSTRAINT FK_3F20704232D562B FOREIGN KEY (object_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_translation ADD CONSTRAINT FK_B0C8559232D562B FOREIGN KEY (object_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE type_translation ADD CONSTRAINT FK_FF7092FE232D562B FOREIGN KEY (object_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_property_translation ADD CONSTRAINT FK_F9C11A21232D562B FOREIGN KEY (object_id) REFERENCES image_property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_slider_translation ADD CONSTRAINT FK_588A1C3D232D562B FOREIGN KEY (object_id) REFERENCES image_slider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_property ADD CONSTRAINT FK_40292763DA5256D FOREIGN KEY (image_id) REFERENCES property (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_category DROP FOREIGN KEY FK_58CB2D8512469DE2');
        $this->addSql('ALTER TABLE category_translation DROP FOREIGN KEY FK_3F20704232D562B');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEC54C8C93');
        $this->addSql('ALTER TABLE type_translation DROP FOREIGN KEY FK_FF7092FE232D562B');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE8BAC62AF');
        $this->addSql('ALTER TABLE contact_message DROP FOREIGN KEY FK_2C9211FEE7A1254A');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE9395C3F3');
        $this->addSql('ALTER TABLE property_visit DROP FOREIGN KEY FK_856D2834549213EC');
        $this->addSql('ALTER TABLE contact_message DROP FOREIGN KEY FK_2C9211FE549213EC');
        $this->addSql('ALTER TABLE property_category DROP FOREIGN KEY FK_58CB2D85549213EC');
        $this->addSql('ALTER TABLE property_translation DROP FOREIGN KEY FK_B0C8559232D562B');
        $this->addSql('ALTER TABLE image_property DROP FOREIGN KEY FK_40292763DA5256D');
        $this->addSql('ALTER TABLE image_slider_translation DROP FOREIGN KEY FK_588A1C3D232D562B');
        $this->addSql('ALTER TABLE image_property_translation DROP FOREIGN KEY FK_F9C11A21232D562B');
        $this->addSql('DROP TABLE fos_user_group');
        $this->addSql('DROP TABLE fos_user_user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE property_visit');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_category');
        $this->addSql('DROP TABLE image_slider');
        $this->addSql('DROP TABLE category_translation');
        $this->addSql('DROP TABLE property_translation');
        $this->addSql('DROP TABLE type_translation');
        $this->addSql('DROP TABLE image_property_translation');
        $this->addSql('DROP TABLE image_slider_translation');
        $this->addSql('DROP TABLE image_property');
    }
}
