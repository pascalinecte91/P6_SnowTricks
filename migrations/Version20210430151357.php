<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430151357 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD created_at DATETIME NOT NULL, ADD update_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP created_at, DROP update_at');
        $this->addSql('ALTER TABLE comment CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL');
    }
}
