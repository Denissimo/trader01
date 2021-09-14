<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914130535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE purse_id_seq CASCADE');
        $this->addSql('DROP TABLE purse');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE SEQUENCE purse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE purse (id INT NOT NULL, user_id INT NOT NULL, currency_id INT NOT NULL, amount NUMERIC(35, 21) DEFAULT \'0\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_dae44a038248176 ON purse (currency_id)');
        $this->addSql('CREATE INDEX idx_dae44a0a76ed395 ON purse (user_id)');
        $this->addSql('COMMENT ON COLUMN purse.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN purse.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE purse ADD CONSTRAINT fk_dae44a0a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purse ADD CONSTRAINT fk_dae44a038248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
