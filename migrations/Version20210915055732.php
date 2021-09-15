<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915055732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_tree_id_seq CASCADE');
        $this->addSql('DROP TABLE user_tree');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_tree_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_tree (id INT NOT NULL, child_user_id INT NOT NULL, parent_user_id INT NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_cdbf1215c5da9b8e ON user_tree (child_user_id)');
        $this->addSql('CREATE INDEX idx_cdbf1215d526a7d3 ON user_tree (parent_user_id)');
        $this->addSql('ALTER TABLE user_tree ADD CONSTRAINT fk_cdbf1215c5da9b8e FOREIGN KEY (child_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_tree ADD CONSTRAINT fk_cdbf1215d526a7d3 FOREIGN KEY (parent_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
