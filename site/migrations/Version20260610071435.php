<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610071435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendrier CHANGE signature signature LONGTEXT NOT NULL, CHANGE tournee_id tournee_id INT NOT NULL, CHANGE prenom_pompier prenom_pompier VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendrier CHANGE prenom_pompier prenom_pompier VARCHAR(100) DEFAULT NULL, CHANGE signature signature LONGTEXT DEFAULT NULL, CHANGE tournee_id tournee_id INT DEFAULT NULL');
    }
}
