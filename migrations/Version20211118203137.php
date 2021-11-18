<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118203137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, latitude NUMERIC(10, 7) NOT NULL, longitude NUMERIC(10, 7) NOT NULL, country VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE measurement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id_id INTEGER NOT NULL, temperature DOUBLE PRECISION NOT NULL, humidity DOUBLE PRECISION DEFAULT NULL, wind_strength DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2CE0D8113CCE3900 ON measurement (city_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE measurement');
    }
}
