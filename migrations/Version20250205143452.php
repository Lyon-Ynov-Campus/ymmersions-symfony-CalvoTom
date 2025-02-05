<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205143452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matchs (id INT AUTO_INCREMENT NOT NULL, id_tournament_id INT NOT NULL, id_team_1_id INT NOT NULL, id_team_2_id INT NOT NULL, date DATE NOT NULL, score_team_1 INT DEFAULT NULL, score_team_2 INT DEFAULT NULL, INDEX IDX_6B1E60418DF97282 (id_tournament_id), INDEX IDX_6B1E6041A10714A6 (id_team_1_id), INDEX IDX_6B1E6041B3B2BB48 (id_team_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E60418DF97282 FOREIGN KEY (id_tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041A10714A6 FOREIGN KEY (id_team_1_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041B3B2BB48 FOREIGN KEY (id_team_2_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E60418DF97282');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041A10714A6');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041B3B2BB48');
        $this->addSql('DROP TABLE matchs');
    }
}
