<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119081949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, house_number VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_detail (id INT AUTO_INCREMENT NOT NULL, telephone_number VARCHAR(255) NOT NULL, fax VARCHAR(255) NOT NULL, internet_site VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, organization_type_id INT NOT NULL, contact_detail_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C1EE637CF5B7AF75 (address_id), INDEX IDX_C1EE637C89E04D0 (organization_type_id), INDEX IDX_C1EE637CB62120C0 (contact_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, address_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, INDEX IDX_34DCD17632C8A3DE (organization_id), INDEX IDX_34DCD176F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_label (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, label_id INT DEFAULT NULL, is_blacklisted TINYINT(1) NOT NULL, is_vip TINYINT(1) NOT NULL, is_contactable TINYINT(1) NOT NULL, salutation TINYTEXT NOT NULL, INDEX IDX_A2914F2C217BBB47 (person_id), INDEX IDX_A2914F2C33B92F39 (label_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, is_activated TINYINT(1) NOT NULL, activation_hash VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CB62120C0 FOREIGN KEY (contact_detail_id) REFERENCES contact_detail (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17632C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE person_label ADD CONSTRAINT FK_A2914F2C217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_label ADD CONSTRAINT FK_A2914F2C33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CF5B7AF75');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176F5B7AF75');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CB62120C0');
        $this->addSql('ALTER TABLE person_label DROP FOREIGN KEY FK_A2914F2C33B92F39');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17632C8A3DE');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C89E04D0');
        $this->addSql('ALTER TABLE person_label DROP FOREIGN KEY FK_A2914F2C217BBB47');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE contact_detail');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_type');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_label');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE `user`');
    }
}
