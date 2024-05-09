<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509201911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM publicacion');
        $this->addSql('DELETE FROM embarcacion');
     
        $this->addSql('ALTER TABLE amarra DROP "tama�no"');
        $this->addSql('ALTER TABLE embarcacion ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE embarcacion ADD CONSTRAINT FK_77F67C2FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_77F67C2FDB38439E ON embarcacion (usuario_id)');
        $this->addSql('ALTER TABLE publicacion ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_62F2085FDB38439E ON publicacion (usuario_id)');
        $this->addSql('ALTER TABLE usuario ADD amarra_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD dni VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD cuil VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD nombre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD apellido VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD telefono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D145CE053 FOREIGN KEY (amarra_id) REFERENCES amarra (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D145CE053 ON usuario (amarra_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE embarcacion DROP CONSTRAINT FK_77F67C2FDB38439E');
        $this->addSql('DROP INDEX IDX_77F67C2FDB38439E');
        $this->addSql('ALTER TABLE embarcacion DROP usuario_id');
        $this->addSql('ALTER TABLE amarra ADD "tama�no" VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT FK_2265B05D145CE053');
        $this->addSql('DROP INDEX UNIQ_2265B05D145CE053');
        $this->addSql('ALTER TABLE usuario DROP amarra_id');
        $this->addSql('ALTER TABLE usuario DROP dni');
        $this->addSql('ALTER TABLE usuario DROP cuil');
        $this->addSql('ALTER TABLE usuario DROP nombre');
        $this->addSql('ALTER TABLE usuario DROP apellido');
        $this->addSql('ALTER TABLE usuario DROP telefono');
        $this->addSql('ALTER TABLE usuario DROP direccion');
        $this->addSql('ALTER TABLE publicacion DROP CONSTRAINT FK_62F2085FDB38439E');
        $this->addSql('DROP INDEX IDX_62F2085FDB38439E');
        $this->addSql('ALTER TABLE publicacion DROP usuario_id');
    }
}
