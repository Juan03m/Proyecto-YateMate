<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619192817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion_amarra ALTER fecha_desde TYPE DATE');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER fecha_hasta TYPE DATE');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER numero DROP NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER sector DROP NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER marina DROP NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER tamano DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER fecha_desde TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER fecha_hasta TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER numero SET NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER sector SET NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER marina SET NOT NULL');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER tamano SET NOT NULL');
    }
}
