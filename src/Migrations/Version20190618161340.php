<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618161340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE result_question (id INT AUTO_INCREMENT NOT NULL, result_id INT DEFAULT NULL, question_id INT DEFAULT NULL, notation INT NOT NULL, comment LONGTEXT NOT NULL, photo LONGTEXT DEFAULT NULL, INDEX IDX_11F256AD7A7B643 (result_id), INDEX IDX_11F256AD1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_category (id INT AUTO_INCREMENT NOT NULL, survey_id INT NOT NULL, title VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5ABB5FE6B3FE509D (survey_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, area_id INT DEFAULT NULL, entity_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649AF73D997 (direction_id), INDEX IDX_8D93D649BD0F409C (area_id), INDEX IDX_8D93D64981257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result_team_member (id INT AUTO_INCREMENT NOT NULL, result_id INT DEFAULT NULL, first_name LONGTEXT NOT NULL, last_name LONGTEXT NOT NULL, role LONGTEXT NOT NULL, INDEX IDX_106B29EE7A7B643 (result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, survey_id INT DEFAULT NULL, user_id INT DEFAULT NULL, direction_id INT DEFAULT NULL, area_id INT DEFAULT NULL, entity_id INT DEFAULT NULL, date DATETIME NOT NULL, place LONGTEXT NOT NULL, client LONGTEXT NOT NULL, validated TINYINT(1) NOT NULL, best_practice_done TINYINT(1) NOT NULL, best_practice_comment LONGTEXT NOT NULL, best_practice_photo LONGTEXT NOT NULL, INDEX IDX_136AC113B3FE509D (survey_id), INDEX IDX_136AC113A76ED395 (user_id), INDEX IDX_136AC113AF73D997 (direction_id), INDEX IDX_136AC113BD0F409C (area_id), INDEX IDX_136AC11381257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D7943D68AF73D997 (direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE direction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dangerous_situation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entity (id INT AUTO_INCREMENT NOT NULL, area_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E284468BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey_question (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, label LONGTEXT NOT NULL, help LONGTEXT DEFAULT NULL, INDEX IDX_EA000F6912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dangerous_situation_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, type LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_9F159CB32C2AC5D3 (translatable_id), UNIQUE INDEX dangerous_situation_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE header (id INT AUTO_INCREMENT NOT NULL, logo_text VARCHAR(255) NOT NULL, news LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE survey (id INT AUTO_INCREMENT NOT NULL, direction_id INT NOT NULL, title VARCHAR(255) NOT NULL, team VARCHAR(255) NOT NULL, count_team INT DEFAULT NULL, best_practice_label LONGTEXT NOT NULL, best_practice_help LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_AD5F9BFCAF73D997 (direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_practice_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, type LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_D088C1782C2AC5D3 (translatable_id), UNIQUE INDEX best_practice_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE best_practice (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result_question ADD CONSTRAINT FK_11F256AD7A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
        $this->addSql('ALTER TABLE result_question ADD CONSTRAINT FK_11F256AD1E27F6BF FOREIGN KEY (question_id) REFERENCES survey_question (id)');
        $this->addSql('ALTER TABLE survey_category ADD CONSTRAINT FK_5ABB5FE6B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64981257D5D FOREIGN KEY (entity_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE result_team_member ADD CONSTRAINT FK_106B29EE7A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113B3FE509D FOREIGN KEY (survey_id) REFERENCES survey (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113AF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11381257D5D FOREIGN KEY (entity_id) REFERENCES entity (id)');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68AF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E284468BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE survey_question ADD CONSTRAINT FK_EA000F6912469DE2 FOREIGN KEY (category_id) REFERENCES survey_category (id)');
        $this->addSql('ALTER TABLE dangerous_situation_translation ADD CONSTRAINT FK_9F159CB32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES dangerous_situation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE survey ADD CONSTRAINT FK_AD5F9BFCAF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE best_practice_translation ADD CONSTRAINT FK_D088C1782C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES best_practice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE survey_question DROP FOREIGN KEY FK_EA000F6912469DE2');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A76ED395');
        $this->addSql('ALTER TABLE result_question DROP FOREIGN KEY FK_11F256AD7A7B643');
        $this->addSql('ALTER TABLE result_team_member DROP FOREIGN KEY FK_106B29EE7A7B643');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BD0F409C');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113BD0F409C');
        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E284468BD0F409C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF73D997');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113AF73D997');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68AF73D997');
        $this->addSql('ALTER TABLE survey DROP FOREIGN KEY FK_AD5F9BFCAF73D997');
        $this->addSql('ALTER TABLE dangerous_situation_translation DROP FOREIGN KEY FK_9F159CB32C2AC5D3');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64981257D5D');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11381257D5D');
        $this->addSql('ALTER TABLE result_question DROP FOREIGN KEY FK_11F256AD1E27F6BF');
        $this->addSql('ALTER TABLE survey_category DROP FOREIGN KEY FK_5ABB5FE6B3FE509D');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113B3FE509D');
        $this->addSql('ALTER TABLE best_practice_translation DROP FOREIGN KEY FK_D088C1782C2AC5D3');
        $this->addSql('DROP TABLE result_question');
        $this->addSql('DROP TABLE survey_category');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE result_team_member');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE dangerous_situation');
        $this->addSql('DROP TABLE entity');
        $this->addSql('DROP TABLE survey_question');
        $this->addSql('DROP TABLE dangerous_situation_translation');
        $this->addSql('DROP TABLE header');
        $this->addSql('DROP TABLE survey');
        $this->addSql('DROP TABLE best_practice_translation');
        $this->addSql('DROP TABLE best_practice');
    }
}
