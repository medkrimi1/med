<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117101127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work_experience (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, company VARCHAR(255) DEFAULT NULL, startdate VARCHAR(255) DEFAULT NULL, enddate VARCHAR(255) DEFAULT NULL, envi VARCHAR(255) DEFAULT NULL, duties VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidates DROP phonecode, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE work_experience');
        $this->addSql('ALTER TABLE candidates ADD phonecode VARCHAR(255) DEFAULT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
