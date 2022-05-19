<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217092517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sub_organization_type (id INT AUTO_INCREMENT NOT NULL, organization_type_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_F7CE3A5E89E04D0 (organization_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sub_organization_type ADD CONSTRAINT FK_F7CE3A5E89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sub_organization_type');
    }
}
