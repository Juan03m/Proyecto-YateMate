<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240518221639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE solicitud_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE solicitud (id INT NOT NULL, solicitado_id INT NOT NULL, solicitante_id INT NOT NULL, embarcacion_id INT NOT NULL, bien_id INT NOT NULL, descripcion VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_96D27CC0C2828A38 ON solicitud (solicitado_id)');
        $this->addSql('CREATE INDEX IDX_96D27CC0C680A87 ON solicitud (solicitante_id)');
        $this->addSql('CREATE INDEX IDX_96D27CC08204A549 ON solicitud (embarcacion_id)');
        $this->addSql('CREATE INDEX IDX_96D27CC0BD95B80F ON solicitud (bien_id)');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0C2828A38 FOREIGN KEY (solicitado_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0C680A87 FOREIGN KEY (solicitante_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC08204A549 FOREIGN KEY (embarcacion_id) REFERENCES embarcacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE solicitud_id_seq CASCADE');
        $this->addSql('ALTER TABLE solicitud DROP CONSTRAINT FK_96D27CC0C2828A38');
        $this->addSql('ALTER TABLE solicitud DROP CONSTRAINT FK_96D27CC0C680A87');
        $this->addSql('ALTER TABLE solicitud DROP CONSTRAINT FK_96D27CC08204A549');
        $this->addSql('ALTER TABLE solicitud DROP CONSTRAINT FK_96D27CC0BD95B80F');
        $this->addSql('DROP TABLE solicitud');
    }
}
