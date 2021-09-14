<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914074253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE accurals_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE accurals (id INT NOT NULL, user_id INT NOT NULL, source_user_id INT DEFAULT NULL, amount_usd DOUBLE PRECISION NOT NULL, amount_btc DOUBLE PRECISION NOT NULL, amount_etn DOUBLE PRECISION NOT NULL, comment VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F408F652A76ED395 ON accurals (user_id)');
        $this->addSql('CREATE INDEX IDX_F408F652EEB16BFD ON accurals (source_user_id)');
        $this->addSql('COMMENT ON COLUMN accurals.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN accurals.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE accurals ADD CONSTRAINT FK_F408F652A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE accurals ADD CONSTRAINT FK_F408F652EEB16BFD FOREIGN KEY (source_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE accurals_id_seq CASCADE');
        $this->addSql('DROP TABLE accurals');
    }
}
