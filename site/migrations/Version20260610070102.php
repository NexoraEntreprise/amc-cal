<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260610070102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendrier ADD tournee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE calendrier ADD CONSTRAINT FK_B2753CB9F661D013 FOREIGN KEY (tournee_id) REFERENCES tournee (id)');
        $this->addSql('CREATE INDEX IDX_B2753CB9F661D013 ON calendrier (tournee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendrier DROP FOREIGN KEY FK_B2753CB9F661D013');
        $this->addSql('DROP INDEX IDX_B2753CB9F661D013 ON calendrier');
        $this->addSql('ALTER TABLE calendrier DROP tournee_id');
    }
}
