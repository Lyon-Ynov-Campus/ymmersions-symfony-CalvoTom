<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204142649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matchs (id INT AUTO_INCREMENT NOT NULL, id_tournament_id INT NOT NULL, id_team_1_id INT NOT NULL, id_team_2_id INT NOT NULL, date DATE NOT NULL, score_team_1 INT DEFAULT NULL, score_team_2 INT DEFAULT NULL, INDEX IDX_6B1E60418DF97282 (id_tournament_id), INDEX IDX_6B1E6041A10714A6 (id_team_1_id), INDEX IDX_6B1E6041B3B2BB48 (id_team_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE register (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_team_id INT NOT NULL, id_tournament_id INT NOT NULL, INDEX IDX_5FF9401479F37AE5 (id_user_id), INDEX IDX_5FF94014F7F171DE (id_team_id), INDEX IDX_5FF940148DF97282 (id_tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date_start_register DATE NOT NULL, date_end_register DATE NOT NULL, nb_max_team INT NOT NULL, nb_max_by_team INT NOT NULL, date_start DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E60418DF97282 FOREIGN KEY (id_tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041A10714A6 FOREIGN KEY (id_team_1_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041B3B2BB48 FOREIGN KEY (id_team_2_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF9401479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF94014F7F171DE FOREIGN KEY (id_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE register ADD CONSTRAINT FK_5FF940148DF97282 FOREIGN KEY (id_tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E60418DF97282');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041A10714A6');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041B3B2BB48');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF9401479F37AE5');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF94014F7F171DE');
        $this->addSql('ALTER TABLE register DROP FOREIGN KEY FK_5FF940148DF97282');
        $this->addSql('DROP TABLE matchs');
        $this->addSql('DROP TABLE register');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
