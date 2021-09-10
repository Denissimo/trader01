<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909084523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD hash VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649727ACA70 ON "user" (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649727ACA70');
        $this->addSql('DROP INDEX IDX_8D93D649727ACA70');
        $this->addSql('ALTER TABLE "user" DROP parent_id');
        $this->addSql('ALTER TABLE "user" DROP hash');
    }
}
