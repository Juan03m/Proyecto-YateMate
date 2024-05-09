<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509154237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM amarra');
        $this->addSql('CREATE SEQUENCE bien_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE bien (id INT NOT NULL, owner_id INT NOT NULL, tipo VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_45EDC3867E3C61F9 ON bien (owner_id)');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC3867E3C61F9 FOREIGN KEY (owner_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE amarra ADD embarcacion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amarra ADD sector INT NOT NULL ' );
        $this->addSql('ALTER TABLE amarra ADD marina VARCHAR(255) NOT NULL' );
        $this->addSql('ALTER TABLE amarra ADD tama�no VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE amarra ADD CONSTRAINT FK_E09BA8428204A549 FOREIGN KEY (embarcacion_id) REFERENCES embarcacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E09BA8428204A549 ON amarra (embarcacion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE bien_id_seq CASCADE');
        $this->addSql('ALTER TABLE bien DROP CONSTRAINT FK_45EDC3867E3C61F9');
        $this->addSql('DROP TABLE bien');
        $this->addSql('ALTER TABLE amarra DROP CONSTRAINT FK_E09BA8428204A549');
        $this->addSql('DROP INDEX UNIQ_E09BA8428204A549');
        $this->addSql('ALTER TABLE amarra DROP embarcacion_id');
        $this->addSql('ALTER TABLE amarra DROP sector');
        $this->addSql('ALTER TABLE amarra DROP marina');
        $this->addSql('ALTER TABLE amarra DROP tama�no');
    }
}
