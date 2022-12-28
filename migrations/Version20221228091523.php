<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228091523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs CHANGE presentation presentation VARCHAR(4000) NOT NULL, CHANGE resp resp VARCHAR(4000) NOT NULL, CHANGE req req VARCHAR(4000) NOT NULL');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5F56E2B44 FOREIGN KEY (typeid_id) REFERENCES type_jobs (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5D26628FA FOREIGN KEY (exp_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B591BD8781');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B591BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id)');
        $this->addSql('ALTER TABLE work_experience DROP FOREIGN KEY FK_1EF36CD091BD8781');
        $this->addSql('ALTER TABLE work_experience ADD CONSTRAINT FK_1EF36CD091BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5F56E2B44');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5D26628FA');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5F92F3E70');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5A76ED395');
        $this->addSql('ALTER TABLE jobs CHANGE presentation presentation VARCHAR(2000) NOT NULL, CHANGE resp resp VARCHAR(2000) NOT NULL, CHANGE req req VARCHAR(2000) NOT NULL');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B591BD8781');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B591BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_experience DROP FOREIGN KEY FK_1EF36CD091BD8781');
        $this->addSql('ALTER TABLE work_experience ADD CONSTRAINT FK_1EF36CD091BD8781 FOREIGN KEY (candidate_id) REFERENCES candidates (id) ON DELETE CASCADE');
    }
}
