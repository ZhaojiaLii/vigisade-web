<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190611141051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D7943D68AF73D997 (direction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entity (id INT AUTO_INCREMENT NOT NULL, area_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E284468BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE direction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, area_id INT DEFAULT NULL, entity_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649AF73D997 (direction_id), INDEX IDX_8D93D649BD0F409C (area_id), INDEX IDX_8D93D64981257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE header (id INT AUTO_INCREMENT NOT NULL, logo_text VARCHAR(255) NOT NULL, news LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE area ADD CONSTRAINT FK_D7943D68AF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E284468BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AF73D997 FOREIGN KEY (direction_id) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64981257D5D FOREIGN KEY (entity_id) REFERENCES entity (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E284468BD0F409C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BD0F409C');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64981257D5D');
        $this->addSql('ALTER TABLE area DROP FOREIGN KEY FK_D7943D68AF73D997');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AF73D997');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE entity');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE header');
    }
}
