<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914080449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE accural_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE accural (id INT NOT NULL, user_id INT NOT NULL, source_user_id INT DEFAULT NULL, amount_usd NUMERIC(21, 5) DEFAULT \'0\' NOT NULL, amount_btc NUMERIC(22, 11) DEFAULT \'0\' NOT NULL, amount_eth NUMERIC(32, 21) DEFAULT \'0\' NOT NULL, level INT DEFAULT NULL, comment VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D3297343A76ED395 ON accural (user_id)');
        $this->addSql('CREATE INDEX IDX_D3297343EEB16BFD ON accural (source_user_id)');
        $this->addSql('COMMENT ON COLUMN accural.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN accural.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE accural ADD CONSTRAINT FK_D3297343A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE accural ADD CONSTRAINT FK_D3297343EEB16BFD FOREIGN KEY (source_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE accural_id_seq CASCADE');
        $this->addSql('DROP TABLE accural');
    }
}
