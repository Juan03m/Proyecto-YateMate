<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510141754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE embarcacion ADD alto DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD ancho DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD largo DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion DROP tamano');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE embarcacion ADD tamano VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE embarcacion DROP alto');
        $this->addSql('ALTER TABLE embarcacion DROP ancho');
        $this->addSql('ALTER TABLE embarcacion DROP largo');
    }
}
