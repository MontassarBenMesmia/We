<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505160809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(100) NOT NULL, PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(100) NOT NULL, prix DOUBLE PRECISION NOT NULL, quantite_en_stock INT NOT NULL, image VARCHAR(500) NOT NULL, idCategorie INT DEFAULT NULL, INDEX IDX_29A5EC27B597FD62 (idCategorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B597FD62 FOREIGN KEY (idCategorie) REFERENCES categorie (idCategorie)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B597FD62');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE produit');
    }
}
