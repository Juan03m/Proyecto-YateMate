<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240602205016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM amarra');
        $this->addSql('ALTER TABLE amarra ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE amarra ADD tamaño VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE amarra ADD CONSTRAINT FK_E09BA842DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E09BA842DB38439E ON amarra (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE amarra DROP CONSTRAINT FK_E09BA842DB38439E');
        $this->addSql('DROP INDEX UNIQ_E09BA842DB38439E');
        $this->addSql('ALTER TABLE amarra DROP usuario_id');
        $this->addSql('ALTER TABLE amarra DROP tamaño');
    }
}
