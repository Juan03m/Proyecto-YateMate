<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606135854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_e09ba842db38439e');
        $this->addSql('CREATE INDEX IDX_E09BA842DB38439E ON amarra (usuario_id)');
        $this->addSql('ALTER TABLE usuario DROP CONSTRAINT fk_2265b05d145ce053');
        $this->addSql('DROP INDEX uniq_2265b05d145ce053');
        $this->addSql('ALTER TABLE usuario DROP amarra_id');
        $this->addSql('ALTER TABLE usuario ALTER dni TYPE VARCHAR(8)');
        $this->addSql('ALTER TABLE usuario ALTER cuil TYPE VARCHAR(11)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE usuario ADD amarra_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ALTER dni TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE usuario ALTER cuil TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT fk_2265b05d145ce053 FOREIGN KEY (amarra_id) REFERENCES amarra (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_2265b05d145ce053 ON usuario (amarra_id)');
        $this->addSql('DROP INDEX IDX_E09BA842DB38439E');
        $this->addSql('CREATE UNIQUE INDEX uniq_e09ba842db38439e ON amarra (usuario_id)');
    }
}
