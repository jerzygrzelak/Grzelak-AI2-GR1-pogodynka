<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118215218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('DROP INDEX IDX_2CE0D8113CCE3900');
        $this->addSql('CREATE TEMPORARY TABLE __temp__measurement AS SELECT id, city_id_id, temperature, humidity, wind_strength, description, date FROM measurement');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('CREATE TABLE measurement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id_id INTEGER NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION DEFAULT NULL, wind_strength DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, date DATE NOT NULL, CONSTRAINT FK_2CE0D8113CCE3900 FOREIGN KEY (city_id_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO measurement (id, city_id_id, temperature, humidity, wind_strength, description, date) SELECT id, city_id_id, temperature, humidity, wind_strength, description, date FROM __temp__measurement');
        $this->addSql('DROP TABLE __temp__measurement');
        $this->addSql('CREATE INDEX IDX_2CE0D8113CCE3900 ON measurement (city_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_2CE0D8113CCE3900');
        $this->addSql('CREATE TEMPORARY TABLE __temp__measurement AS SELECT id, city_id_id, temperature, humidity, wind_strength, description, date FROM measurement');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('CREATE TABLE measurement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id_id INTEGER NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION DEFAULT NULL, wind_strength DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date DATE NOT NULL)');
        $this->addSql('INSERT INTO measurement (id, city_id_id, temperature, humidity, wind_strength, description, date) SELECT id, city_id_id, temperature, humidity, wind_strength, description, date FROM __temp__measurement');
        $this->addSql('DROP TABLE __temp__measurement');
        $this->addSql('CREATE INDEX IDX_2CE0D8113CCE3900 ON measurement (city_id_id)');
    }
}
