<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426231338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamations ADD reponse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamations ADD CONSTRAINT FK_1CAD6B76CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1CAD6B76CF18BB82 ON reclamations (reponse_id)');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('DROP INDEX IDX_5FB6DEC72D6BA2D9 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP etat, DROP reclamation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reclamations DROP FOREIGN KEY FK_1CAD6B76CF18BB82');
        $this->addSql('DROP INDEX UNIQ_1CAD6B76CF18BB82 ON reclamations');
        $this->addSql('ALTER TABLE reclamations DROP reponse_id');
        $this->addSql('ALTER TABLE reponse ADD etat VARCHAR(255) NOT NULL, ADD reclamation_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamations (id)');
        $this->addSql('CREATE INDEX IDX_5FB6DEC72D6BA2D9 ON reponse (reclamation_id)');
    }
}
