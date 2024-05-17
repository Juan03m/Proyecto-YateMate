<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517192043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE embarcacion ADD manga DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD eslora DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD puntal DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion DROP alto');
        $this->addSql('ALTER TABLE embarcacion DROP ancho');
        $this->addSql('ALTER TABLE embarcacion DROP largo');
        $this->addSql('ALTER TABLE embarcacion ALTER usuario_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE embarcacion ADD alto DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD ancho DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD largo DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE embarcacion DROP manga');
        $this->addSql('ALTER TABLE embarcacion DROP eslora');
        $this->addSql('ALTER TABLE embarcacion DROP puntal');
        $this->addSql('ALTER TABLE embarcacion ALTER usuario_id SET NOT NULL');
    }
}
