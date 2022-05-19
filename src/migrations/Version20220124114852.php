<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124114852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_organization_type (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, organization_type_id INT DEFAULT NULL, INDEX IDX_F425F86A32C8A3DE (organization_id), INDEX IDX_F425F86A89E04D0 (organization_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_organization_type ADD CONSTRAINT FK_F425F86A32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE organization_organization_type ADD CONSTRAINT FK_F425F86A89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C89E04D0');
        $this->addSql('DROP INDEX IDX_C1EE637C89E04D0 ON organization');
        $this->addSql('ALTER TABLE organization DROP organization_type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE organization_organization_type');
        $this->addSql('ALTER TABLE organization ADD organization_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C89E04D0 ON organization (organization_type_id)');
    }
}
