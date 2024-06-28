<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627233111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM reserva_amarra');
        $this->addSql('DELETE FROM publicacion_amarra');
        $this->addSql('DELETE FROM amarra');

        $this->addSql('DROP INDEX uniq_83bd6847145ce053');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER amarra_id DROP NOT NULL');
        $this->addSql('CREATE INDEX IDX_83BD6847145CE053 ON publicacion_amarra (amarra_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_83BD6847145CE053');
        $this->addSql('ALTER TABLE publicacion_amarra ALTER amarra_id SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_83bd6847145ce053 ON publicacion_amarra (amarra_id)');
    }
}
