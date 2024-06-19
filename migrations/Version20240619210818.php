<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619210818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE reserva_amarra_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reserva_amarra (id INT NOT NULL, publicacion_amarra_id INT NOT NULL, solicitante_id INT NOT NULL, aceptada BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_52B1D17CB607068 ON reserva_amarra (publicacion_amarra_id)');
        $this->addSql('CREATE INDEX IDX_52B1D17CC680A87 ON reserva_amarra (solicitante_id)');
        $this->addSql('ALTER TABLE reserva_amarra ADD CONSTRAINT FK_52B1D17CB607068 FOREIGN KEY (publicacion_amarra_id) REFERENCES publicacion_amarra (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reserva_amarra ADD CONSTRAINT FK_52B1D17CC680A87 FOREIGN KEY (solicitante_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE reserva_amarra_id_seq CASCADE');
        $this->addSql('ALTER TABLE reserva_amarra DROP CONSTRAINT FK_52B1D17CB607068');
        $this->addSql('ALTER TABLE reserva_amarra DROP CONSTRAINT FK_52B1D17CC680A87');
        $this->addSql('DROP TABLE reserva_amarra');
    }
}
