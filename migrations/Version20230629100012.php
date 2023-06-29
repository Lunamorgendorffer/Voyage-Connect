<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629100012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP INDEX UNIQ_5A8A6C8DA76ED395, ADD INDEX IDX_5A8A6C8DA76ED395 (user_id)');
        $this->addSql('ALTER TABLE post ADD country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP INDEX IDX_5A8A6C8DA76ED395, ADD UNIQUE INDEX UNIQ_5A8A6C8DA76ED395 (user_id)');
        $this->addSql('ALTER TABLE post DROP country');
        $this->addSql('ALTER TABLE user DROP avatar');
    }
}
