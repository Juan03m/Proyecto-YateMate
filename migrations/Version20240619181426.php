<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619181426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE publicacion_amarra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE publicacion_amarra (id INT NOT NULL, amarra_id INT NOT NULL, usuario_id INT NOT NULL, fecha_desde TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, fecha_hasta TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, numero INT NOT NULL, sector INT NOT NULL, marina VARCHAR(255) NOT NULL, tamano VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83BD6847145CE053 ON publicacion_amarra (amarra_id)');
        $this->addSql('CREATE INDEX IDX_83BD6847DB38439E ON publicacion_amarra (usuario_id)');
        $this->addSql('ALTER TABLE publicacion_amarra ADD CONSTRAINT FK_83BD6847145CE053 FOREIGN KEY (amarra_id) REFERENCES amarra (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE publicacion_amarra ADD CONSTRAINT FK_83BD6847DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE publicacion_amarra_id_seq CASCADE');
        $this->addSql('ALTER TABLE publicacion_amarra DROP CONSTRAINT FK_83BD6847145CE053');
        $this->addSql('ALTER TABLE publicacion_amarra DROP CONSTRAINT FK_83BD6847DB38439E');
        $this->addSql('DROP TABLE publicacion_amarra');
    }
}
