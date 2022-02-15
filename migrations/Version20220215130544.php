<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215130544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adulte DROP INDEX UNIQ_757FE71ECCD7E912, ADD INDEX IDX_757FE71ECCD7E912 (menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adulte DROP INDEX IDX_757FE71ECCD7E912, ADD UNIQUE INDEX UNIQ_757FE71ECCD7E912 (menu_id)');
    }
}
