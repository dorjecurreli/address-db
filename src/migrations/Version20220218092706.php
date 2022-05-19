<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220218092706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_type ADD sub_organization_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE organization_type ADD CONSTRAINT FK_B5D769B45916F7B3 FOREIGN KEY (sub_organization_type_id) REFERENCES sub_organization_type (id)');
        $this->addSql('CREATE INDEX IDX_B5D769B45916F7B3 ON organization_type (sub_organization_type_id)');
        $this->addSql('ALTER TABLE sub_organization_type DROP FOREIGN KEY FK_F7CE3A5E89E04D0');
        $this->addSql('DROP INDEX IDX_F7CE3A5E89E04D0 ON sub_organization_type');
        $this->addSql('ALTER TABLE sub_organization_type DROP organization_type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_type DROP FOREIGN KEY FK_B5D769B45916F7B3');
        $this->addSql('DROP INDEX IDX_B5D769B45916F7B3 ON organization_type');
        $this->addSql('ALTER TABLE organization_type DROP sub_organization_type_id');
        $this->addSql('ALTER TABLE sub_organization_type ADD organization_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sub_organization_type ADD CONSTRAINT FK_F7CE3A5E89E04D0 FOREIGN KEY (organization_type_id) REFERENCES organization_type (id)');
        $this->addSql('CREATE INDEX IDX_F7CE3A5E89E04D0 ON sub_organization_type (organization_type_id)');
    }
}
