<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611145307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE description description VARCHAR(65535) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD avatar VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE description description MEDIUMTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP avatar, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
