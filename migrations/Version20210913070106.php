<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913070106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE deal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE deal (id INT NOT NULL, user_id INT NOT NULL, amount_usd NUMERIC(18, 2) DEFAULT \'0\' NOT NULL, amount_btc NUMERIC(19, 8) DEFAULT \'0\' NOT NULL, amount_eth NUMERIC(29, 18) DEFAULT \'0\' NOT NULL, purpose VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3FEC116A76ED395 ON deal (user_id)');
        $this->addSql('COMMENT ON COLUMN deal.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN deal.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC116A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account RENAME COLUMN usd TO amount_usd');
        $this->addSql('ALTER TABLE account RENAME COLUMN btc TO amount_btc');
        $this->addSql('ALTER TABLE account RENAME COLUMN eth TO amount_eth');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE deal_id_seq CASCADE');
        $this->addSql('DROP TABLE deal');
        $this->addSql('ALTER TABLE account RENAME COLUMN amount_usd TO usd');
        $this->addSql('ALTER TABLE account RENAME COLUMN amount_btc TO btc');
        $this->addSql('ALTER TABLE account RENAME COLUMN amount_eth TO eth');
    }
}
