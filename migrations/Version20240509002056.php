<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240509002056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE publicacion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE publicacion (id INT NOT NULL, embarcacion_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62F2085F8204A549 ON publicacion (embarcacion_id)');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085F8204A549 FOREIGN KEY (embarcacion_id) REFERENCES embarcacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE publicacion_id_seq CASCADE');
        $this->addSql('ALTER TABLE publicacion DROP CONSTRAINT FK_62F2085F8204A549');
        $this->addSql('DROP TABLE publicacion');
    }
}
