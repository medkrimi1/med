<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117101247 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_experience ADD candidate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_experience ADD CONSTRAINT FK_1EF36CD091BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id)');
        $this->addSql('CREATE INDEX IDX_1EF36CD091BD8781 ON work_experience (candidate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_experience DROP FOREIGN KEY FK_1EF36CD091BD8781');
        $this->addSql('DROP INDEX IDX_1EF36CD091BD8781 ON work_experience');
        $this->addSql('ALTER TABLE work_experience DROP candidate_id');
    }
}
