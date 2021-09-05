<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210826060754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adulte (id INT AUTO_INCREMENT NOT NULL, menu_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, resume LONGTEXT DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, tags VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_757FE71ECCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adulte ADD CONSTRAINT FK_757FE71ECCD7E912 FOREIGN KEY (menu_id) REFERENCES menu_adulte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE adulte');
    }
}
