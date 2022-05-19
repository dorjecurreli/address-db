<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223083708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_type DROP FOREIGN KEY FK_B5D769B45916F7B3');
        $this->addSql('DROP INDEX IDX_B5D769B45916F7B3 ON organization_type');
        $this->addSql('ALTER TABLE organization_type CHANGE sub_organization_type_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization_type ADD CONSTRAINT FK_B5D769B4727ACA70 FOREIGN KEY (parent_id) REFERENCES organization_type (id)');
        $this->addSql('CREATE INDEX IDX_B5D769B4727ACA70 ON organization_type (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_type DROP FOREIGN KEY FK_B5D769B4727ACA70');
        $this->addSql('DROP INDEX IDX_B5D769B4727ACA70 ON organization_type');
        $this->addSql('ALTER TABLE organization_type CHANGE parent_id sub_organization_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization_type ADD CONSTRAINT FK_B5D769B45916F7B3 FOREIGN KEY (sub_organization_type_id) REFERENCES sub_organization_type (id)');
        $this->addSql('CREATE INDEX IDX_B5D769B45916F7B3 ON organization_type (sub_organization_type_id)');
    }
}
