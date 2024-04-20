<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420171709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_reclamations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reclamations ADD type_reclamations_id INT NOT NULL, DROP type_reclamation');
        $this->addSql('ALTER TABLE reclamations ADD CONSTRAINT FK_1CAD6B76EF672883 FOREIGN KEY (type_reclamations_id) REFERENCES type_reclamations (id)');
        $this->addSql('CREATE INDEX IDX_1CAD6B76EF672883 ON reclamations (type_reclamations_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE type_reclamations');
        $this->addSql('ALTER TABLE reclamations DROP FOREIGN KEY FK_1CAD6B76EF672883');
        $this->addSql('DROP INDEX IDX_1CAD6B76EF672883 ON reclamations');
        $this->addSql('ALTER TABLE reclamations ADD type_reclamation VARCHAR(255) NOT NULL, DROP type_reclamations_id');
    }
}
