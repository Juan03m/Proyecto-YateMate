<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621202301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion_amarra DROP esta_vigente');
        $this->addSql('ALTER TABLE publicacion_amarra DROP esta_alquilada');
        $this->addSql('DROP INDEX idx_52b1d17cb607068');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_52B1D17CB607068 ON reserva_amarra (publicacion_amarra_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publicacion_amarra ADD esta_vigente BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ADD esta_alquilada BOOLEAN NOT NULL');
        $this->addSql('DROP INDEX UNIQ_52B1D17CB607068');
        $this->addSql('CREATE INDEX idx_52b1d17cb607068 ON reserva_amarra (publicacion_amarra_id)');
    }
}
