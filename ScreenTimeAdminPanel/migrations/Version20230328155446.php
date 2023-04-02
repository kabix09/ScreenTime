<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328155446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT IDENTITY NOT NULL, nationality_id INT NOT NULL, name NVARCHAR(55) NOT NULL, surname NVARCHAR(70) NOT NULL, birth_date DATETIME2(6) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_447556F91C9DA55 ON actor (nationality_id)');
        $this->addSql('CREATE TABLE character (id INT IDENTITY NOT NULL, actor_id INT NOT NULL, role_name NVARCHAR(55) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_937AB03410DAF24A ON character (actor_id)');
        $this->addSql('CREATE UNIQUE INDEX UQ_Character_Signature ON character (role_name, actor_id) WHERE role_name IS NOT NULL AND actor_id IS NOT NULL');
        $this->addSql('CREATE TABLE country (id INT IDENTITY NOT NULL, iso VARCHAR(MAX) NOT NULL, name NVARCHAR(80) NOT NULL, nicename NVARCHAR(80) NOT NULL, iso3 NVARCHAR(3), numcode INT, phonecode INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE genre (id INT IDENTITY NOT NULL, name NVARCHAR(80) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UQ_Character_Signature ON genre (name) WHERE name IS NOT NULL');
        $this->addSql('CREATE TABLE movie (id INT IDENTITY NOT NULL, title NVARCHAR(255) NOT NULL, production_year DATETIME2(6) NOT NULL, duration_time INT NOT NULL, world_premiere_date DATETIME2(6) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UQ_Movie_Signature ON movie (title, production_year) WHERE title IS NOT NULL AND production_year IS NOT NULL');
        $this->addSql('CREATE TABLE movie_character (movie_id INT NOT NULL, character_id INT NOT NULL, time_on_scene INT NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6) NOT NULL, PRIMARY KEY (movie_id, character_id))');
        $this->addSql('CREATE INDEX IDX_AFB9328F93B6FC ON movie_character (movie_id)');
        $this->addSql('CREATE INDEX IDX_AFB9321136BE75 ON movie_character (character_id)');
        $this->addSql('CREATE TABLE movie_genre (movie_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY (movie_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F91C9DA55 FOREIGN KEY (nationality_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id)');
        $this->addSql('ALTER TABLE movie_character ADD CONSTRAINT FK_AFB9328F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_character ADD CONSTRAINT FK_AFB9321136BE75 FOREIGN KEY (character_id) REFERENCES character (id)');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F91C9DA55');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03410DAF24A');
        $this->addSql('ALTER TABLE movie_character DROP CONSTRAINT FK_AFB9328F93B6FC');
        $this->addSql('ALTER TABLE movie_character DROP CONSTRAINT FK_AFB9321136BE75');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229648F93B6FC');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229644296D31F');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_character');
        $this->addSql('DROP TABLE movie_genre');
    }
}
